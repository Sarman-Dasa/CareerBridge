<script setup lang="ts">
import { useUserStore } from "../useUserStore";
import About from "./About.vue";
// import ActivityTimeline from './ActivityTimeline.vue'
import { postRequest, putRequest } from "@/services/apiService";
import Connection from "./Connection.vue";
const router = useRoute();

const profileTabData = ref();
const connectionsList = ref();

const userStore = useUserStore();
profileTabData.value = userStore.user;
const perPage = ref(3);
const page = ref(1);
const isConnectionLoader = ref(true);

async function getUserConnection() {
  isConnectionLoader.value = true

  const input = {
    per_page: perPage.value,
    page: page.value,
  };

  let response = await postRequest("/connection", input, false);
  if (response && response.status == 200) {
    connectionsList.value = response.data;
  }
  isConnectionLoader.value = false
}

async function updateConnectionStatus(event: { id: string; status: string }) {
  isConnectionLoader.value = true;
  let response = await putRequest(`/connection/update/${event.id}`,{status:event.status});

  if(response && response.status == 200) {
    getUserConnection();
  }

  isConnectionLoader.value = false;
}

onMounted(() => {
  getUserConnection();
});
</script>

<template>
  <VRow v-if="profileTabData">
    <VCol md="4" cols="12">
      <About :data="profileTabData" />
    </VCol>

    <VCol cols="12" md="8">
      <VRow>
        <!-- <VCol cols="12">
          <ActivityTimeline />
        </VCol> -->

        <VCol cols="12" md="6">
          <Connection
            :connections-data="connectionsList"
            v-if="!isConnectionLoader"
            @update-connection="updateConnectionStatus"
          />
        </VCol>

        <VCol cols="12" md="6">
          <Teams :teams-data="profileTabData.teamsTech" />
        </VCol>

        <VCol cols="12">
          <ProjectList />
        </VCol>
      </VRow>
    </VCol>
  </VRow>
</template>
