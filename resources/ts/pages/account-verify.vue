<script setup lang="ts">
import { postRequest } from '@/services/apiService';
const route = useRoute()
const router = useRouter()

const email = ref(route.query.email);
const token = ref(route.query.token);


async function verifyUserAccount() {
  let response  = await  postRequest('/account-verify',{
      email: email.value,
      token: token.value
    });

    if(response && response.status == 200) {
      router.replace("login");
    }
}


onMounted(() => {
  verifyUserAccount();
})


</script>

<template>
  <div>
    <VCard title="Create Awesome 🙌">
      <VCardText>This is your second page.</VCardText>
      <VCardText>
        <p>Email {{ email }}</p>
      </VCardText>
    </VCard>
  </div>
</template>

<style lang="scss" scoped></style>
