<script lang="ts" setup>
import type { ProfileTab } from '@/@fake-db/types';
import UserInfoEditDialog from '@/components/dialogs/UserInfoEditDialog.vue';
import { postRequest } from '@/services/apiService';


interface Props {
  data: ProfileTab
}
const props = defineProps<Props>()

const isUserInfoEditDialogVisible = ref(false)

async function updateProfile(event: any) {

  const formData = new FormData();
  const { first_name, last_name, email, mobile, city, profile_image, username } = event;


  formData.append('first_name', first_name);
  formData.append('last_name', last_name);
  formData.append('username', username);
  formData.append('email', email);
  formData.append('mobile', mobile);
  formData.append('city', city);
  if (profile_image) {
    console.log('profile_image: ', profile_image);
    formData.append('profile_image', profile_image);
  }

  const response = await postRequest('/user/update-profile', formData, false,{
    headers: {
    "Content-Type": null,
  },
  });

  if (response && response.status == 200) {
    let user = response.data.user;
    localStorage.setItem('userData', JSON.stringify(user))
  }
}
</script>

<template>
  <VCard title="ABOUT">
    <template #append>
      <div class="me-n2">
        <VBtn variant="none" class="me-4" size="34" prepend-icon="mdi-account-edit"
          @click="isUserInfoEditDialogVisible = true">
          <VTooltip activator="parent" location="top">Update profile</VTooltip>
        </VBtn>
      </div>
    </template>
    <VCardText>
      <!-- ABOUT Section -->

      <VList class="card-list text-medium-emphasis">
        <VListItem>
          <template #prepend>
            <VIcon icon="mdi-account" size="20" class="me-2" />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Full Name:</span>
            <span>{{ props.data.full_name }}</span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon icon="mdi-badge-account" size="20" class="me-2" />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Role:</span>
            <span>{{ props.data.role_name }}</span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon icon="mdi-account-outline" size="20" class="me-2" />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Username:</span>
            <span>{{ props.data.username }}</span>
          </VListItemTitle>
        </VListItem>
      </VList>

      <!-- CONTACTS Section -->
      <p class="text-xs mt-5">CONTACTS</p>
      <VList class="card-list text-medium-emphasis">
        <VListItem>
          <template #prepend>
            <VIcon icon="mdi-email" size="20" class="me-2" />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Email:</span>
            <span>{{ props.data.email }}</span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon icon="mdi-phone" size="20" class="me-2" />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Mobile:</span>
            <span>{{ props.data.mobile }}</span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon icon="mdi-city" size="20" class="me-2" />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">City:</span>
            <span>{{ props.data.city }}</span>
          </VListItemTitle>
        </VListItem>
      </VList>

      <!-- TEAMS Section (Optional, add as needed) -->

      <!-- OVERVIEW Section -->
      <p class="text-xs mt-5">OVERVIEW</p>
      <VList class="card-list text-medium-emphasis">
        <VListItem>
          <template #prepend>
            <VIcon icon="mdi-check-circle" size="20" class="me-2" />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Account Status:</span>
            <span>{{ props.data.is_active ? "Active" : 'Deactivate' }}</span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon icon="mdi-calendar" size="20" class="me-2" />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Join At:</span>
            <span>{{ $formatDate(props.data.created_at) }}</span>
          </VListItemTitle>
        </VListItem>
        <VListItem>
          <template #prepend>
            <VIcon icon="mdi-account-outline" size="20" class="me-2" />
          </template>
          <VListItemTitle>
            <span class="font-weight-medium me-1">Connections:</span>
            <span>{{ props.data.connection_count ?? 0 }}</span>
          </VListItemTitle>
        </VListItem>
      </VList>
    </VCardText>
  </VCard>

  <UserInfoEditDialog v-model:isDialogVisible="isUserInfoEditDialogVisible" :user-data="props.data"
    @submit="updateProfile" />
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 16px;
}
</style>
