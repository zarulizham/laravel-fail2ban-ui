<template>
  <StatCard :title="title" :count="count" :loading="loading" />
</template>

<script setup>
import { onMounted, ref } from "vue";
import StatCard from "./StatCard.vue";

const title = ref("Recurring IPs (7 days)");
const count = ref(0);
const loading = ref(true);

const load = async () => {
  loading.value = true;

  try {
    const response = await fetch("/api/fail2ban-ui/recurring-ips");
    const payload = await response.json();

    title.value = payload.label ?? "Recurring IPs (7 days)";
    count.value = payload.count ?? 0;
  } catch {
    title.value = "Recurring IPs (7 days)";
    count.value = 0;
  } finally {
    loading.value = false;
  }
};

onMounted(load);
</script>
