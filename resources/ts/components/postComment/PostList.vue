<script setup lang="ts">
import { deleteRequest, postRequest } from '@/services/apiService';
import { useRoute, useRouter } from 'vue-router';
import type { Post } from '../type';

const route = useRoute()
const router = useRouter()


interface Props {
  post:Post
}

interface Emit {
  (e: 'getPostList'): void
}

const props = defineProps<Props>()
const emit = defineEmits<Emit>()

const items = ref([
        { title: 'Edit Post', value: 'edit', icon: 'mdi-pencil' },
        { title: 'Delete Post', value: 'delete', icon: 'mdi-delete' },
        {title: 'Post Setting', value: 'postSetting', icon: 'mdi-cog'}
      ])
      
function getProfileImageUrl(imagePath: string) {
  if (!imagePath) return null;
  const baseUrl = import.meta.env.VITE_API_IMAGE_PATH;
  return `${baseUrl}${imagePath}`;
}


function openPost(id:string) {
  router.push({ name: 'PostDetail', params: { id: id }})
}

async function updatePostLike(id:string) {
  const response = await postRequest(`/post/like-dislike/${id}`, {}, false);

  if (response && response.status == 200) {
    let data = response.data;
    emit('getPostList')
  }
}

function handleItemClick(item: { title: string; value: string }) {
  console.log('Clicked item:', item);
  if (item.value === 'edit') {
    // Handle edit action
  } else if (item.value === 'delete') {
    deletePost(props.post.id)
  } else if (item.value === 'postSetting') {
    // Handle post setting action
  }
}

async function deletePost(id:string) {
  console.log('id: ', id);
  const response = await deleteRequest(`/post/delete/${id}`, {});
  if (response && response.status == 200) {
    emit('getPostList')
  }
}

</script>
<template>
  <v-card class="post-card mx-auto">
    <!-- Post Header -->
    <v-card-title class="text-h6 text-truncate d-flex justify-space-between">
  {{ post.title }}
  <MoreBtn :menuList="items" @item-click="handleItemClick" />
</v-card-title>

    
    <!-- Post Content -->
    <v-card-text class="text-body-2 text-truncate">
      {{ post.content }}
    </v-card-text>

    <!-- Attachments as Slider -->
    <v-divider></v-divider>
    <v-card-text v-if="post.attachments?.length" class="px-0">
      <v-carousel 
        v-if="post.attachments?.length" 
        height="auto"
        class="post-carousel"
        cycle 
        hide-delimiter-background 
        :show-arrows="post.attachments?.length > 1">
        <v-carousel-item 
          v-for="attachment in post.attachments" 
          :key="attachment.id" 
          :src="getProfileImageUrl(attachment.file_path)" 
          cover>
        </v-carousel-item>
      </v-carousel>
      <div class="like-comment-count-list">
        <p>
          <VIcon icon="mdi-thumb-up" size="18"></VIcon> 
          <span class="ml-1">{{ post.likeCount }}</span>
        </p>
        <p><strong>Comments:</strong> {{ post.commentCount }}</p>
      </div>
    </v-card-text>

    <!-- Actions -->
    <v-divider></v-divider>
    <v-card-text class="action-icons">
      <div class="icon-list" @click="updatePostLike(post.id)">
        <VIcon v-if="!post.hasLike" icon="mdi-thumbs-up-outline"></VIcon>
        <VIcon v-else icon="mdi-thumbs-up" color="primary"></VIcon>
        <span class="text-xs">Like</span>
      </div>
      <div class="icon-list" @click="openPost(post.id)">
        <VIcon icon="mdi-comment-text-outline"></VIcon>
        <span class="text-xs">Comment</span>
      </div>
      <div class="icon-list">
        <VIcon icon="mdi-repeat-variant"></VIcon>
        <span class="text-xs">Share</span>
      </div>
      <div class="icon-list">
        <VIcon icon="mdi-send-variant"></VIcon>
        <span class="text-xs">Send</span>
      </div>
    </v-card-text>
  </v-card>
</template>

