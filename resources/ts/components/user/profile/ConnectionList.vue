<script lang="ts" setup>
import userImg from "@images/avatars/avatar-1.png";
import { defineEmits, defineProps } from 'vue';

interface Connection {
  id: string;
  full_name: string;
  connection_count: number;
  profile_image: string | null;
  pivot: {
    status: string;
    user_id: string;
  };
}

interface Props {
  connections: Array<Connection>;
  iconDirection: string;
  iconColor: string;
  connectionType: 'received' | 'sent';
}

const props = defineProps<Props>();
const emit = defineEmits(['updateStatus']);

function handleUpdateStatus(event: { id: string; status: string }) {
  emit('updateStatus', event);
}
</script>

<template>
  <VListItem v-for="data in props.connections" :key="data.id">
    <template #prepend>
      <VAvatar size="38" :image="data.profile_image ?? userImg" />
    </template>

    <VListItemTitle class="font-weight-medium">
      {{ data.full_name }}
      <VIcon :icon="props.iconDirection" :color="props.iconColor" size="10" />
    </VListItemTitle>
    <VListItemSubtitle>{{ data.connection_count }} Connections</VListItemSubtitle>

    <template #append>
      <ConnectionActionButtons :data="data" :connection-type="props.connectionType" @update-status="handleUpdateStatus" />
    </template>
  </VListItem>
</template>
