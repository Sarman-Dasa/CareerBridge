<script setup lang="ts">
import VueDropzone from "dropzone-vue3";
import { ref } from "vue";

// Props
const props = defineProps({
  isOpen: {
    type: Boolean,
    required: true,
  },
});

type PostSetting = {
  whoCanSeePost: 'P' | 'C';
  commentControl: 'A' | 'C' | 'N';
};

const emit = defineEmits("update:isOpen", "addNewPost");

// State
const isDialogOpen = ref(props.isOpen);
const postContent = ref("");
const title = ref();
const postAttachment = ref<File[]>([]);
const dropzoneOptions = {
  url: `${import.meta.env.VITE_API_URL}/image-upload`,
  maxFilesize: 10, // Max file size in MB
  addRemoveLinks: true,
  uploadMultiple: false,
  acceptedFiles: ".jpg, .jpeg, .png, .gif", // Accepted file types
};

const openFilePreview = ref(false)
const fileInput = ref<InstanceType<typeof VueDropzone> | null>(null);

const isOpenPostSettingModal = ref(false)
const postSetting = ref<PostSetting>({
  whoCanSeePost: 'P',
  commentControl: 'A',
})

const postStatus = ref(false);

// Methods
const closeDialog = () => {
  isDialogOpen.value = false;
  emit("update:isOpen", false);
};

const onFileAdded = (file: any) => {
  postAttachment.value.push(file);
  openFilePreview.value = true
};

const submitPost = () => {
  if (postContent.value.trim() === "") {
    return;
  }

  const postData = {
    content: postContent.value,
    title: title.value,
    attachment: postAttachment.value,
    visibility: postSetting.value.whoCanSeePost,
    comment_control: postSetting.value.commentControl,
    status:postStatus.value ? 'D' : 'P' //P = Public, D = Save as Draff
  };

  emit('addNewPost', postData);
  closeDialog();
};

function addAttachment() {
  openFilePreview.value = false
}

function closeFilePreviewModal() {
  postAttachment.value = []
  openFilePreview.value = false
}

function setPostSetting(data: PostSetting) {
  postSetting.value = data
  isOpenPostSettingModal.value = false
}

watch(
  () => props.isOpen,
  (newVal) => {
    isDialogOpen.value = newVal;
  }
);
</script>

<template>
  <v-dialog v-model="isDialogOpen" max-width="600px">
    <DialogCloseBtn @click="closeDialog" />
    <v-card>
      <v-card-title class="d-flex justify-space-between">
        <span class="headline">Create a Post</span>
        <VIcon icon="mdi-cog-outline" size="18" class="mr-3 mt-1" @click="isOpenPostSettingModal = true"></VIcon>
      </v-card-title>

      <v-card-subtitle class="grey--text text--darken-1">
        Share your thoughts with your network
      </v-card-subtitle>

      <v-card-text>
        <v-text-field v-model="title" label="title" outlined> </v-text-field>
        <!-- Post Content Input -->
        <v-textarea v-model="postContent" label="What's on your mind?" outlined rows="4" class="mt-4"></v-textarea>

        <!-- File Attachment Section -->
        <VueDropzone id="dropzone" ref="fileInput" :options="dropzoneOptions" @vdropzone-success="onFileAdded"
          class="d-none" />
        <!-- Edit Icon Overlay -->
        <div class="edit-icon mt-4 d-flex justify-space-between">
          <VIcon icon="mdi-image-add-outline" @click="fileInput.$el.click()"></VIcon>
          <!-- Small card for preview image show -->
          <v-row class="flex-wrap justify-end" dense @click="openFilePreview = true">
            <!-- Display up to 3 images -->
            <v-col v-for="(file, index) in postAttachment.slice(0, 3)" :key="index" cols="auto"
              class="d-flex justify-center align-center" style="padding: 0; margin-left: -10px;">
              <v-img :src="file.dataURL" alt="Image preview" class="small-image-preview" contain />
            </v-col>

            <!-- Show count of additional images -->
            <div v-if="postAttachment?.length > 3" class="preview-image-count">
              +{{ postAttachment.length - 3 }}
            </div>
          </v-row>
        </div>
      </v-card-text>
      <v-card-actions>
        <v-checkbox v-model="postStatus" label="Save this post as a draft?" />
        
        <v-spacer></v-spacer>
        <v-btn text @click="closeDialog">Cancel</v-btn>
        <v-btn color="primary" @click="submitPost">Post</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

  <FilePreview v-if="openFilePreview" :show-modal="openFilePreview" :files="postAttachment"
    @close-modal="closeFilePreviewModal" @addMoreImage="fileInput.$el.click()" @send="addAttachment" />

  <PostSetting :show-modal="isOpenPostSettingModal" :post-setting="postSetting"
    @close-modal="isOpenPostSettingModal = false" @update-setting="setPostSetting" />
</template>

<style scoped lang="scss">
.vue-dropzone:hover {
  background: transparent;
}

.small-image-preview {
  width: 24px;
  height: 24px;
  border-radius: 10px;
  transform: rotate(15deg);
  /* Rotate the images slightly */
  overflow: hidden;
  border: 2px solid white;
  cursor: pointer;
}


.preview-image-count {
  width: 34px;
  height: 34px;
  display: flex;
  justify-content: center;
  align-items: center;
  background-color: rgba(0, 0, 0, 0.6);
  color: white;
  border-radius: 10px;
  font-size: 12px;
  margin-left: -10px;
}
</style>
