<template>
  <StatCard :title="title" :count="count" :loading="loading" />
</template>

<script setup>
import { onMounted, ref } from "vue";
import StatCard from "./StatCard.vue";

const title = ref("New Last Hour");
const count = ref(0);
const loading = ref(true);

const load = async () => {
  loading.value = true;

  try {
    const response = await fetch("/api/fail2ban-ui/new-last-hour");
    const payload = await response.json();

    title.value = payload.label ?? "New Last Hour";
    count.value = payload.count ?? 0;
  } catch {
    title.value = "New Last Hour";
    count.value = 0;
  } finally {
    loading.value = false;
  }
};

onMounted(load);
</script>
