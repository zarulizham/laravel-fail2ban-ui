<template>
  <div class="card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h6 class="fw-semibold mb-1">{{ title }}</h6>
        <small class="text-muted">{{ subtitle }}</small>
      </div>
      <div class="w-25">
        <input
          v-model="search"
          type="text"
          class="form-control form-control-sm"
          placeholder="Search IP or jail"
        />
      </div>
    </div>

    <div
      v-if="feedback.message"
      :class="[
        'alert py-2 px-3 small',
        feedback.type === 'error' ? 'alert-danger' : 'alert-success',
      ]"
      role="alert"
    >
      {{ feedback.message }}
    </div>

    <div v-if="loading" class="text-muted small">Loading overview...</div>

    <div v-else class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Jail</th>
            <th>Total</th>
            <th>Last Hour</th>
            <th>Banned IPs</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in filteredRows" :key="row.jail">
            <td class="fw-medium">{{ row.jail }}</td>
            <td>{{ row.total }}</td>
            <td>{{ row.lastHour }}</td>
            <td>
              <div
                v-for="ip in row.bannedIps"
                :key="ip"
                class="d-flex justify-content-between align-items-center mb-2"
              >
                <span>{{ ip }}</span>
                <button
                  type="button"
                  class="btn btn-warning btn-sm"
                  :disabled="isUnbanning(row.jail, ip)"
                  @click="unbanIp(row.jail, ip)"
                >
                  {{ isUnbanning(row.jail, ip) ? "Unbanning..." : "Unban" }}
                </button>
              </div>
              <small v-if="row.more" class="text-muted"
                >+{{ row.more }} more</small
              >
            </td>
          </tr>
          <tr v-if="filteredRows.length === 0">
            <td colspan="4" class="text-muted text-center">
              No records found.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";

const title = ref("Overview Active Jails and Blocks");
const subtitle = ref("Filter banned IPs or manage jail configuration.");
const rows = ref([]);
const loading = ref(true);
const search = ref("");
const unbanning = ref({});
const feedback = ref({ type: "success", message: "" });

const csrfToken =
  document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ??
  "";

const normalizeRow = (row) => {
  const bannedIps = Array.isArray(row.bannedIps)
    ? row.bannedIps
    : Array.isArray(row.banned_ip_list)
    ? row.banned_ip_list
    : [];

  return {
    jail: row.jail ?? "-",
    total: row.total ?? row.total_banned ?? 0,
    lastHour: row.lastHour ?? row.currently_banned ?? 0,
    bannedIps,
    more: row.more ?? Math.max(0, bannedIps.length - 1),
  };
};

const load = async () => {
  loading.value = true;
  feedback.value = { type: "success", message: "" };

  try {
    const response = await fetch("/api/fail2ban-ui/overview");
    const payload = await response.json();

    title.value = payload.title ?? title.value;
    subtitle.value = payload.subtitle ?? subtitle.value;

    const incomingRows = Array.isArray(payload.rows) ? payload.rows : [];
    rows.value = incomingRows.map(normalizeRow);
  } catch {
    rows.value = [];
  } finally {
    loading.value = false;
  }
};

const stateKey = (serviceName, ip) => `${serviceName}:${ip}`;

const isUnbanning = (serviceName, ip) =>
  unbanning.value[stateKey(serviceName, ip)] === true;

const unbanIp = async (serviceName, ip) => {
  const key = stateKey(serviceName, ip);

  unbanning.value = {
    ...unbanning.value,
    [key]: true,
  };

  feedback.value = { type: "success", message: "" };

  try {
    const response = await fetch("/api/fail2ban-ui/unban-ip", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": csrfToken,
      },
      body: JSON.stringify({
        service_name: serviceName,
        ip,
      }),
    });

    const payload = await response.json().catch(() => ({}));

    if (!response.ok) {
      throw new Error(payload.message ?? "Failed to unban IP.");
    }

    feedback.value = {
      type: "success",
      message: payload.message ?? `IP ${ip} unbanned from ${serviceName}.`,
    };

    await load();
  } catch (error) {
    feedback.value = {
      type: "error",
      message: error instanceof Error ? error.message : "Failed to unban IP.",
    };
  } finally {
    unbanning.value = {
      ...unbanning.value,
      [key]: false,
    };
  }
};

const filteredRows = computed(() => {
  const query = search.value.trim().toLowerCase();

  if (!query) {
    return rows.value;
  }

  return rows.value.filter((row) => {
    return (
      row.jail.toLowerCase().includes(query) ||
      row.bannedIps.some((ip) => ip.toLowerCase().includes(query))
    );
  });
});

onMounted(load);
</script>
