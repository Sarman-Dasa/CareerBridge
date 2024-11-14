<script setup lang="ts">
interface UserData {
  id: string;
  first_name: string;
  last_name: string;
  username: string;
  city: string;
  email: string;
  mobile: string;
  profile_image: string;
}

interface Props {
  userData?: UserData;
  isDialogVisible: boolean;
}

interface Emit {
  (e: "submit", value: UserData): void;
  (e: "update:isDialogVisible", val: boolean): void;
}

const props = withDefaults(defineProps<Props>(), {
  userData: () => ({
    id: "",
    first_name: "",
    last_name: "",
    city: "",
    username: "",
    mobile: "",
    profile_image: "",
    email: "",
  }),
});

const emit = defineEmits<Emit>();

const userData = ref<UserData>(structuredClone(toRaw(props.userData)));

watch(props, () => {
  userData.value = structuredClone(toRaw(props.userData));
});

const onFormSubmit = () => {
  emit("update:isDialogVisible", false);
  emit("submit", userData.value);
};

const onFormReset = () => {
  userData.value = structuredClone(toRaw(props.userData));

  emit("update:isDialogVisible", false);
};

const dialogModelValueUpdate = (val: boolean) => {
  emit("update:isDialogVisible", val);
};
</script>

<template>
  <VDialog :width="$vuetify.display.smAndDown ? 'auto' : 677" :model-value="props.isDialogVisible"
    @update:model-value="dialogModelValueUpdate">
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <VCard class="pa-sm-8 pa-5">
      <VCardItem class="text-center">
        <VCardTitle class="text-h5 mb-3">Update Profile </VCardTitle>
      </VCardItem>

      <VCardText>
        <!-- 👉 Form -->
        <VForm class="mt-2" @submit.prevent="onFormSubmit">
          <VRow>
            <!-- 👉 First Name -->
            <VCol cols="12" md="6">
              <AppTextField v-model="userData.first_name" label="first Name" />
            </VCol>

            <!-- 👉 Last Name -->
            <VCol cols="12" md="6">
              <AppTextField v-model="userData.last_name" label="Last Name" />
            </VCol>

            <!--  Email -->
            <VCol cols="12" md="6">
              <AppTextField v-model="userData.email" label="Email" />
            </VCol>

            <!-- 👉 Contact -->
            <VCol cols="12" md="6">
              <AppTextField v-model="userData.mobile" label="Contact" />
            </VCol>

            <!-- 👉 userName -->
            <VCol cols="12" md="6">
              <AppTextField v-model="userData.username" label="User Name" />
            </VCol>

            <!-- 👉 Country -->
            <VCol cols="12" md="6">
              <AppTextField v-model="userData.city" label="Country" />
            </VCol>

            <!-- 👉 Submit and Cancel -->
            <VCol cols="12" class="d-flex flex-wrap justify-center gap-4">
              <VBtn type="submit"> Submit </VBtn>

              <VBtn color="secondary" variant="tonal" @click="onFormReset">
                Cancel
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
