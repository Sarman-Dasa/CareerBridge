<script lang="ts" setup>
import UserConnections from '@/pages/user-profile/connections/index.vue';
import UserPost from "@/pages/user-profile/post/index.vue";
import UserProfile from '@/pages/user-profile/profile/index.vue';
import UserProfileHeader from '@/pages/user-profile/UserProfileHeader.vue';
import { useRoute } from 'vue-router';

const route = useRoute()
const router = useRouter()

// Set default tab if not defined
const activeTab = ref(route.params.tab || 'profile')

console.log('activeTab: ', activeTab);

// tabs
const tabs = [
  { title: 'Profile', icon: 'tabler-user-check', tab: 'profile' },
  { title: 'Connections', icon: 'tabler-link', tab: 'connections' },
  { title: 'Post', icon: 'tabler-link', tab: 'post' },
]
</script>

<template>
  <div>
    <UserProfileHeader class="mb-5" />

    <VTabs v-model="activeTab" class="v-tabs-pill">
      <VTab v-for="item in tabs" :key="item.icon" :value="item.tab"
        :to="{ name: 'user-user-profile-tab', params: { tab: item.tab } }">
        <VIcon size="20" start :icon="item.icon" />
        {{ item.title }}
      </VTab>
    </VTabs>

    <VWindow v-model="activeTab" class="mt-5 disable-tab-transition" :touch="false">
      <!-- Profile -->
      <VWindowItem value="profile">
        <UserProfile />
      </VWindowItem>

      <!-- Connections -->
      <VWindowItem value="connections">
        <UserConnections />
      </VWindowItem>

      <!-- Post -->
      <VWindowItem value="post">
        <UserPost />
      </VWindowItem>

    </VWindow>
  </div>
</template>

<route lang="yaml">
meta:
  navActiveLink: user-user-profile-tab
</route>
