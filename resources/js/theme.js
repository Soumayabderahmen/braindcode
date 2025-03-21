import { ref, onMounted } from "vue";

export function useTheme() {
  const isDark = ref(localStorage.getItem("theme") === "dark");

  const toggleDarkMode = () => {
    isDark.value = !isDark.value;
    document.body.classList.toggle("dark", isDark.value);
    localStorage.setItem("theme", isDark.value ? "dark" : "light");
  };

  onMounted(() => {
    if (isDark.value) document.body.classList.add("dark");
  });

  return { isDark, toggleDarkMode };
}
