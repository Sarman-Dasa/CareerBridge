<script setup lang="ts">
import authV2RegisterIllustrationBorderedDark from "@images/pages/auth-v2-register-illustration-bordered-dark.png";
import authV2RegisterIllustrationBorderedLight from "@images/pages/auth-v2-register-illustration-bordered-light.png";
import authV2RegisterIllustrationDark from "@images/pages/auth-v2-register-illustration-dark.png";
import authV2RegisterIllustrationLight from "@images/pages/auth-v2-register-illustration-light.png";
import authV2MaskDark from "@images/pages/misc-mask-dark.png";
import authV2MaskLight from "@images/pages/misc-mask-light.png";
import { VForm } from "vuetify/components/VForm";

import { postRequest } from "@/services/apiService";
import AuthProvider from "@/views/pages/authentication/AuthProvider.vue";
import { useGenerateImageVariant } from "@core/composable/useGenerateImageVariant";
import {
  alphaDashValidator,
  emailValidator,
  requiredValidator,
} from "@validators";

const refVForm = ref<VForm>();
const username = ref();
const email = ref();
const password = ref();
const password_confirmation = ref();
const first_name = ref();
const last_name = ref();
const privacyPolicies = ref(true);

// Router
const route = useRoute();
const router = useRouter();

// Form Errors
const errors = ref<Record<string, string | undefined>>({
  email: undefined,
  password: undefined,
});

const register = async () => {

  let response = await postRequest("/api/register", {
    first_name:first_name.value,
    last_name:last_name.value,
    username: username.value,
    email: email.value,
    password: password.value,
    password_confirmation: password_confirmation.value,
    role: "C",
  });
  if (response && response.status == 200) {
    // Redirect to `to` query if exist or redirect to index route
    router.replace("login");
  }
};

const imageVariant = useGenerateImageVariant(
  authV2RegisterIllustrationLight,
  authV2RegisterIllustrationDark,
  authV2RegisterIllustrationBorderedLight,
  authV2RegisterIllustrationBorderedDark,
  true
);

const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark);

const isPasswordVisible = ref(false);

const onSubmit = () => {
  refVForm.value?.validate().then(({ valid: isValid }) => {
    if (isValid) register();
  });
};
</script>

<template>
  <VRow no-gutters class="auth-wrapper bg-surface">
    <VCol lg="8" class="d-none d-lg-flex">
      <div class="position-relative bg-background rounded-lg w-100 ma-8 me-0">
        <div class="d-flex align-center justify-center w-100 h-100">
          <VImg
            max-width="441"
            :src="imageVariant"
            class="auth-illustration mt-16 mb-2"
          />
        </div>

        <VImg class="auth-footer-mask" :src="authThemeMask" />
      </div>
    </VCol>

    <VCol cols="12" lg="4" class="d-flex align-center justify-center">
      <VCard flat :max-width="500" class="mt-12 mt-sm-0 pa-4">
        <VCardText>
          <VForm ref="refVForm" @submit.prevent="onSubmit">
            <VRow>
              <!-- firstName -->
              <VCol cols="12">
                <AppTextField
                  v-model="first_name"
                  autofocus
                  :rules="[requiredValidator, alphaDashValidator]"
                  label="First name"
                />
              </VCol>

              <!-- lastName -->
              <VCol cols="12">
                <AppTextField
                  v-model="last_name"
                  autofocus
                  :rules="[requiredValidator, alphaDashValidator]"
                  label="Last name"
                />
              </VCol>
              <!-- Username -->
              <VCol cols="12">
                <AppTextField
                  v-model="username"
                  autofocus
                  :rules="[requiredValidator, alphaDashValidator]"
                  label="Username"
                />
              </VCol>

              <!-- email -->
              <VCol cols="12">
                <AppTextField
                  v-model="email"
                  :rules="[requiredValidator, emailValidator]"
                  label="Email"
                  type="email"
                />
              </VCol>

              <!-- password -->
              <VCol cols="12">
                <AppTextField
                  v-model="password"
                  :rules="[requiredValidator]"
                  label="Password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                  "
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="password_confirmation"
                  :rules="[requiredValidator]"
                  label="Password"
                  :type="isPasswordVisible ? 'text' : 'password'"
                  :append-inner-icon="
                    isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'
                  "
                  @click:append-inner="isPasswordVisible = !isPasswordVisible"
                />

                <div class="d-flex align-center mt-2 mb-4">
                  <VCheckbox
                    id="privacy-policy"
                    v-model="privacyPolicies"
                    :rules="[requiredValidator]"
                    inline
                  >
                    <template #label>
                      <span class="me-1">
                        I agree to
                        <a href="javascript:void(0)" class="text-primary"
                          >privacy policy & terms</a
                        >
                      </span>
                    </template>
                  </VCheckbox>
                </div>

                <VBtn block type="submit"> Sign up </VBtn>
              </VCol>

              <!-- create account -->
              <VCol cols="12" class="text-center text-base">
                <span>Already have an account?</span>
                <RouterLink class="text-primary ms-2" :to="{ name: 'login' }">
                  Sign in instead
                </RouterLink>
              </VCol>

              <VCol cols="12" class="d-flex align-center">
                <VDivider />
                <span class="mx-4">or</span>
                <VDivider />
              </VCol>

              <!-- auth providers -->
              <VCol cols="12" class="text-center">
                <AuthProvider />
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </VCol>
  </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>

<route lang="yaml">
meta:
  layout: blank
  action: read
  subject: Auth
  redirectIfLoggedIn: true
</route>
