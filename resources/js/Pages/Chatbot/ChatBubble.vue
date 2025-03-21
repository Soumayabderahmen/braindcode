<script setup>
defineProps({
    message: Object
});
</script>

<template>
    <div :class="['chat-message', message.sender]">
        {{ message.text }}
    </div>
</template>

<style scoped>
.chat-message {
    padding: 8px;
    margin: 5px 0;
    border-radius: 5px;
    max-width: 80%;
}
.chat-message.user {
    background: #007bff;
    color: white;
    text-align: right;
    align-self: flex-end;
}
.chat-message.bot {
    background: #eee;
    text-align: left;
    align-self: flex-start;
}
</style>
