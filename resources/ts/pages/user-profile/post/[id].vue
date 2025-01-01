<script setup lang="ts">
import PostList from '@/components/postComment/PostList.vue';
import type { Post } from '@/components/type';
import { getRequest, postRequest } from '@/services/apiService';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute()
const router = useRouter()
const postLoader = ref<boolean>(true)
const isLocalDialogVisible = ref<boolean>(false)
const post = ref<Post>({} as Post)

async function getPostList() {
  postLoader.value = true
  let id = route.params.id;
  const response = await getRequest(`/post/view/${id}`);

  if (response && response.status == 200) {
    let data = response.data as { post: Post };
    post.value = data.post;
  }
  postLoader.value = false
}

const comments = ref([]);
const parentId = ref<string | null>(null);

async function getComments() {
  let input = {
    post_id:route.params.id
  }
  let response = await postRequest('/comment', input, false);
  if (response && response.status == 200) {
    comments.value = response.data.comments;
  }
}

async function like(commentId:string){
  let response = await postRequest(`/comment/like-dislike/${commentId}`, {}, false);
  if (response && response.status == 200) {
    getComments();
  }
}

function reply(commentId:string){
  console.log('reply: ', commentId);
  isLocalDialogVisible.value = true;
  parentId.value = commentId;
}

function closeDialog(){
  isLocalDialogVisible.value = false;
  parentId.value = null;
}

function commentAdded(){
  closeDialog();
  getComments();
  getPostList();
}

onMounted(() => {
  getPostList()
  getComments()
})

</script>

<template>
  <div>
    <v-row>
      <v-col cols="12" md="4" class="post_card py-5">
        <PostList :post="post" @get-post-list="getPostList"/>
      </v-col>
      <v-col cols="12" md="8">
        <CommentList :comments="comments" @like="like" @reply="reply" />
        <AddComment :post-id="route.params.id" @comment-added="commentAdded" />
      </v-col>
    </v-row>
    <VDialog
    max-width="600"
    :model-value="isLocalDialogVisible"
    class="add_comment_dialog"
    @update:model-value="closeDialog"
    @keyup.esc="closeDialog"
  >
  <DialogCloseBtn @click="closeDialog" />
    <div class="pa-0 mb-5">
      <AddComment :parent-id="parentId" :post-id="route.params.id" @comment-added="commentAdded"/>
    </div>
  </VDialog>
  </div>
</template>
<style lang="scss">
@import "../../../../styles/styles";
</style>
