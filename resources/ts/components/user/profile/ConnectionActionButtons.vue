<script lang="ts" setup>
import { defineEmits, defineProps } from 'vue';

interface Props {
  data: {
    pivot: {
      status: string;
      user_id: string;
    };
  };
  connectionType: 'received' | 'sent';
}

const props = defineProps<Props>();
const emit = defineEmits(['updateStatus']);

function updateStatus(status: string) {
  const obj = {
    status:status,
    id: props.data.pivot.user_id
  }
  emit('updateStatus', obj);
}
</script>

<template>
  <div v-if="connectionType === 'received'">
    <!-- Show Accept & Reject for received connections in 'Pending' status -->
    <div v-if="props.data.pivot.status === 'P'">
      <VBtn icon size="24" variant="outlined" class="mr-2" @click="updateStatus('A')">
        <VIcon size="18" icon="mdi-check" />
        <VTooltip activator="parent" location="top">Accept</VTooltip>
      </VBtn>
      <VBtn icon size="24" variant="outlined" @click="updateStatus('R')">
        <VIcon size="18" icon="mdi-close" />
        <VTooltip activator="parent" location="top">Reject</VTooltip>
      </VBtn>
    </div>
    <!-- Show 'Connected' for accepted connections -->
    <div v-else>
      <VBtn icon size="30" class="rounded" variant="tonal">
        <VIcon size="20" icon="tabler-user-check" />
        <VTooltip activator="parent" location="top">Connected</VTooltip>
      </VBtn>
    </div>
  </div>

  <div v-else>
    <!-- Sent connections -->
    <!-- Show 'Pending' or 'Connected' for sent connections -->
    <VBtn icon size="30" class="rounded" :variant="props.data.pivot.status === 'P' ? 'elevated' : 'tonal'">
      <VIcon size="20" :icon="props.data.pivot.status === 'P' ? 'tabler-user-x' : 'tabler-user-check'" />
      <VTooltip activator="parent" location="top">
        {{ props.data.pivot.status === 'P' ? 'Pending' : 'Connected' }}
      </VTooltip>
    </VBtn>
  </div>
</template>
