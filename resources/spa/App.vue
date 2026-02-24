<template>
  <div class="min-vh-100 d-flex flex-column">
    <nav class="navbar navbar-expand-lg">
      <div class="container">
        <a class="navbar-brand fw-semibold" href="#">Fail2ban UI</a>
        <span class="badge bg-success me-3">Connected</span>

        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarContent"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div
          class="collapse navbar-collapse justify-content-end"
          id="navbarContent"
        >
          <ul class="navbar-nav mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" href="#">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Filter Debug</a>
            </li>
            <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
          </ul>
          <span class="badge bg-light text-dark ms-3">{{ currentTime }}</span>
        </div>
      </div>
    </nav>

    <div class="container my-5 flex-fill">
      <div
        class="d-flex flex-wrap justify-content-between align-items-center mb-5"
      >
        <div>
          <h4 class="fw-semibold mb-1">Dashboard</h4>
        </div>

        <div class="d-flex gap-2 align-items-center">
          <span class="small"
            >Your ext. IP:
            <strong class="text-primary">176.223.172.185</strong>
          </span>

          <button type="button" class="btn btn-primary btn-sm">
            Manage Servers
          </button>
          <button type="button" class="btn btn-outline-primary btn-sm">
            Manage Jails
          </button>
        </div>
      </div>

      <div class="row g-4 mb-5">
        <div class="col-md-3"><ActiveJails /></div>
        <div class="col-md-3"><TotalBannedIps /></div>
        <div class="col-md-3"><NewLastHour /></div>
        <div class="col-md-3"><RecurringIps /></div>
      </div>

      <OverviewSection />
    </div>

    <footer class="border-top py-3 mt-auto bg-white">
      <div class="container text-center small text-muted">
        © 2026 Fail2ban UI
      </div>
    </footer>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import ActiveJails from "./components/ActiveJails.vue";
import TotalBannedIps from "./components/TotalBannedIps.vue";
import NewLastHour from "./components/NewLastHour.vue";
import RecurringIps from "./components/RecurringIps.vue";
import OverviewSection from "./components/OverviewSection.vue";

const currentTime = ref(new Date().toLocaleTimeString("en-GB"));

onMounted(() => {
  setInterval(() => {
    currentTime.value = new Date().toLocaleTimeString("en-GB");
  }, 1000);
});
</script>
