<script setup lang="ts">
import { postRequest } from '@/services/apiService';
const route = useRoute()
console.log('route: ', route.query);
const router = useRouter()

const email = ref(route.query.email);
const token = ref(route.query.token);


async function verifyUserAccount() {
  let response  = await  postRequest('/api/account-verify',{
      email: email.value,
      token: token.value
    });

    if(response && response.status == 200) {
      router.replace("login");
    }
  // await axiosIns
  //   .post("/api/account-verify", {
  //     email: email.value,
  //     token: token.value
  //   })
  //   .then((response) => {
  //     let data = response.data.data;
  //     let token = data.token;
  //     console.log('token: ', token);
  //     let user_info = data.user;
      
  //   })
  //   .catch((e) => {
  //     console.log("Error", e);
  //   });
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
