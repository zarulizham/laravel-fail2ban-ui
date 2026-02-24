<template>
  <StatCard :title="title" :count="count" :loading="loading" />
</template>

<script setup>
import { onMounted, ref } from "vue";
import StatCard from "./StatCard.vue";

const title = ref("Active Jails");
const count = ref(0);
const loading = ref(true);

const load = async () => {
  loading.value = true;

  try {
    const response = await fetch("/api/fail2ban-ui/active-jails");
    const payload = await response.json();

    title.value = payload.label ?? "Active Jails";
    count.value = payload.count ?? 0;
  } catch {
    title.value = "Active Jails";
    count.value = 0;
  } finally {
    loading.value = false;
  }
};

onMounted(load);
</script>
