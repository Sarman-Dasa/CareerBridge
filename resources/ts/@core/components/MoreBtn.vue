<script lang="ts" setup>
import { defineEmits, defineProps } from 'vue';

interface Props {
  menuList?: Array<{ title: string; value: string; icon: string }>;
  itemProps?: boolean;
}

// Define props
const props = defineProps<Props>();

// Define emits
const emit = defineEmits(['item-click']);

function handleClick(item: { title: string; value: string }) {
  console.log(item);
  emit('item-click', item);
}
</script>

<template>
  <div class="more_btn">
    <IconBtn density="compact" color="disabled">
    <VIcon icon="tabler-dots-vertical" />

    <VMenu v-if="props.menuList" activator="parent">
      <VList>
        <VListItem
          v-for="(item, index) in props.menuList"
          :key="index"
          @click="handleClick(item)"
        >
          <div class="d-flex align-center space-between">
            <VListItemIcon class="mr-2">
              <VIcon :icon="item.icon" />
            </VListItemIcon>
            <VListItemTitle>{{ item.title }}</VListItemTitle>
          </div>
        </VListItem>
      </VList>
    </VMenu>
  </IconBtn>
  </div>
</template>

