import { ref } from 'vue'

const toastMessage = ref('')
const toastType = ref('success') // 'success', 'error', etc.
const showToast = ref(false)

export function useToast() {
  const triggerToast = (message, type = 'success') => {
    toastMessage.value = message
    toastType.value = type
    showToast.value = true
    setTimeout(() => showToast.value = false, 3000)
  }

  return {
    toastMessage,
    toastType,
    showToast,
    triggerToast
  }
}
