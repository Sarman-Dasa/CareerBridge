<script setup lang="ts">
import ConnectionCardView from "@/components/user/Connection.vue";
import { deleteRequest, postRequest, putRequest } from "@/services/apiService";
import { useRoute } from "vue-router";

const router = useRoute();
const connectionData = ref();
const suggestConnectionData = ref();

const perPage = ref(10);
const page = ref(1);
const isConnectionLoader = ref(true);
const isSuggestConnectionLoader = ref(true);

async function getUserConnection() {
  isConnectionLoader.value = true;

  const input = {
    per_page: perPage.value,
    page: page.value,
  };

  let response = await postRequest("/connection", {}, false);
  if (response && response.status == 200) {
    connectionData.value = response.data;
  }
  isConnectionLoader.value = false;
}

async function getSuggestConnections() {
  isSuggestConnectionLoader.value = true;

  const input = {
    per_page: perPage.value,
    page: page.value,
  };

  let response = await postRequest(
    "/connection/suggest-connection",
    {},
    false
  );
  if (response && response.status == 200) {
    suggestConnectionData.value = response.data.suggestedConnections;
  }
  isSuggestConnectionLoader.value = false;
}

async function sendRequest(id:string) {
  isConnectionLoader.value = true;
    let response = await postRequest('/connection/create',{
      connection_id:id
    });

    if(response && response.status == 200) {
      getUserConnection();
      getSuggestConnections();
    }

    isConnectionLoader.value = false;
}

async function updateConnectionStatus(event: { id: string; status: string }) {
  isConnectionLoader.value = true;
  let response = await putRequest(`/connection/update/${event.id}`,{status:event.status});

  if(response && response.status == 200) {
    getUserConnection();
  }

  isConnectionLoader.value = false;
}

async function withdrawRequest(id:string) {
  isConnectionLoader.value = true;
    let response = await deleteRequest(`/connection/delete/${id}`);

    if(response && response.status == 200) {
      getUserConnection();
    }
    isConnectionLoader.value = false;
}

onMounted(() => {
  getUserConnection();
  getSuggestConnections();
});
</script>

<template>
  <!-- Receive connection list -->
  <h2 v-if="!isConnectionLoader" class="pt-5">Received Request</h2>
  <VRow v-if="connectionData?.receivedConnections?.length">
    <VCol
      v-for="data in connectionData.receivedConnections"
      :key="data.id"
      sm="6"
      lg="3"
      cols="12"
    >
      <ConnectionCardView :connections-data="data" connection-type="receive" @update-connection="updateConnectionStatus"/>
    </VCol>
  </VRow>

  <!-- sent connection list -->
  <h2 v-if="!isConnectionLoader" class="pt-5">Sent Connections</h2>
  <VRow v-if="connectionData?.sentConnections?.length">
    <VCol
      v-for="data in connectionData.sentConnections"
      :key="data.id"
      sm="6"
      lg="3"
      cols="12"
    >
      <ConnectionCardView :connections-data="data" @withdraw-request="withdrawRequest"/>
    </VCol>
  </VRow>

  <!-- suggest connection list -->
  <h2 v-if="!isSuggestConnectionLoader" class="pt-5">Suggested Connections</h2>
  <VRow v-if="!isSuggestConnectionLoader">
    <VCol
      v-for="data in suggestConnectionData"
      :key="data.id"
      sm="6"
      lg="3"
      cols="12"
    >
      <ConnectionCardView
        :connections-data="data"
        :is-suggest-connection-data="true"
        @send-request="sendRequest"
      />
    </VCol>
  </VRow>
</template>

<style lang="scss">
.vertical-more {
  position: absolute;
  inset-block-start: 1rem;
  inset-inline-end: 0.5rem;
}
</style>
