<template>
  <div class="card p-3 h-100">
    <small class="text-muted">{{ title }}</small>
    <h3 class="fw-semibold mt-2">{{ loading ? "..." : count }}</h3>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  count: {
    type: [String, Number],
    required: true,
  },
  loading: {
    type: Boolean,
    default: false,
  },
});
</script>
