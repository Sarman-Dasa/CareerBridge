<script setup lang="ts">
import PostList from '@/components/postComment/PostList.vue';
import type { Post } from '@/components/type';
import { postRequest } from '@/services/apiService';
import { ref } from 'vue';

const posts = ref<Post[]>([]);

const postCount = ref<Number>(0);

async function getPostList() {
  const response = await postRequest('/user/post-list', {
    post_status: 'P'
  }, false);

  if (response && response.status == 200) {
    let data = response.data;
    console.log('data: ', data);
    posts.value = data.posts;
    postCount.value = data.count
  }
}

onMounted(() => {
  getPostList()
})
</script>

<template>
  <v-container>
    <v-row>
      <v-col cols="12" class="post_card" v-for="post in posts" :key="post.id">
       
        <PostList :post="post" @get-post-list="getPostList"/>
      </v-col>
    </v-row>
  </v-container>
</template>
<style lang="scss">
@import "../../../../styles/styles";
</style>
