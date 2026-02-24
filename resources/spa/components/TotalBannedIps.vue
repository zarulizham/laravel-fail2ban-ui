<template>
  <StatCard :title="title" :count="count" :loading="loading" />
</template>

<script setup>
import { onMounted, ref } from "vue";
import StatCard from "./StatCard.vue";

const title = ref("Total Banned IPs");
const count = ref(0);
const loading = ref(true);

const load = async () => {
  loading.value = true;

  try {
    const response = await fetch("/api/fail2ban-ui/total-banned-ips");
    const payload = await response.json();

    title.value = payload.label ?? "Total Banned IPs";
    count.value = payload.count ?? 0;
  } catch {
    title.value = "Total Banned IPs";
    count.value = 0;
  } finally {
    loading.value = false;
  }
};

onMounted(load);
</script>
