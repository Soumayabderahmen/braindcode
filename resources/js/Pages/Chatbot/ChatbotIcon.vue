<script setup>
const emit = defineEmits(["click"]);
</script>

<template>
    <div class="chatbot-icon" @click="emit('click')">
        🤖
    </div>
</template>

<style scoped>
.chatbot-icon {
    position: fixed;
    bottom: 20px;
    right: 20px;
    width: 50px;
    height: 50px;
    background: #007bff;
    color: white;
    font-size: 24px;
    text-align: center;
    line-height: 50px;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
}
</style>
