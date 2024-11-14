<script lang="ts" setup>
import { computed } from 'vue';

interface Props {
  connectionsData: {
    count: {
      sent: number;
      received: number;
    };
    receivedConnections: Array<any>;
    sentConnections: Array<any>;
  };
}

const props = defineProps<Props>();
const emit = defineEmits(['updateConnection']);

const sendConnectionCount = computed(() => props.connectionsData.count.sent);
const receivedConnectionCount = computed(() => props.connectionsData.count.received);

const moreList = [
  { title: 'Share connections', value: 'Share connections' },
  { title: 'Suggest edits', value: 'Suggest edits' },
  { title: 'Report Bug', value: 'Report Bug' },
];

function updateConnectionStatus(event: { id: string; status: string }) {
  emit('updateConnection', event);
}
</script>

<template>
  <VCard title="Connection">
    <template #append>
      <div class="me-n2">
        <MoreBtn :menu-list="moreList" />
      </div>
    </template>

    <VCardText>
      <VList class="card-list" v-if="receivedConnectionCount || sendConnectionCount">
        <!-- Received Connections -->
        <ConnectionList
          :connections="props.connectionsData.receivedConnections"
          icon-direction="mdi-arrow-bottom-left"
          icon-color="#3ba30f"
          connection-type="received"
          @update-status="updateConnectionStatus"
        />

        <!-- Sent Connections -->
        <ConnectionList
          :connections="props.connectionsData.sentConnections"
          icon-direction="mdi-arrow-top-right"
          icon-color="#c55959"
           connection-type="send"
          @update-status="updateConnectionStatus"
        />

        <!-- View All Connections -->
        <VListItem>
          <VListItemTitle>
            <VBtn block variant="text" :to="{ name: 'user-user-profile-tab', params: { tab: 'connections' } }">
              View all connections
            </VBtn>
          </VListItemTitle>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 14px;
}
</style>
