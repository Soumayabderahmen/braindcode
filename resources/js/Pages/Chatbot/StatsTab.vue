<script setup>
import { ref, onMounted, computed } from 'vue'
import { Bar } from 'vue-chartjs'
import axios from 'axios'
import { Pie } from 'vue-chartjs'
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  ArcElement,
  BarElement,
  CategoryScale,
  LinearScale, 
} from 'chart.js'
ChartJS.register(
  Title,
  Tooltip,
  Legend,
  ArcElement,
  BarElement,
  CategoryScale,
  LinearScale  
)
const stats = ref({})
const loading = ref(true)

const fetchStats = async () => {
  try {
    const response = await axios.get('/admin/chatbot/stats')
    stats.value = response.data
  } catch (e) {
    console.error("Erreur lors du chargement des stats", e)
  } finally {
    loading.value = false
  }
}

onMounted(fetchStats)

const reactionChartData = computed(() => ({
  labels: ['👍 Positives', '👎 Négatives'],
  datasets: [{
    data: [stats.value.positiveReactions ?? 0, stats.value.negativeReactions ?? 0],
    backgroundColor: ['#38bdf8', '#f43f5e'],
    hoverBackgroundColor: ['#0ea5e9', '#e11d48'],
    borderWidth: 0,
    hoverOffset: 6
  }]
}))

const reactionChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { 
      position: 'bottom',
      labels: {
        usePointStyle: true,
        padding: 20,
        font: {
          size: 12
        }
      }
    },
    title: {
      display: true,
      text: 'Répartition des Réactions',
      font: {
        size: 14,
        weight: 'bold'
      },
      padding: {
        bottom: 15
      }
    },
    tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.8)',
      padding: 12,
      cornerRadius: 8,
      titleFont: {
        size: 14,
        weight: 'bold'
      },
      bodyFont: {
        size: 13
      }
    }
  }
}

const roleChartData = computed(() => ({
  labels: Object.keys(stats.value.roleDistribution || {}),
  datasets: [{
    data: Object.values(stats.value.roleDistribution || {}),
    backgroundColor: ['#6366f1', '#0ea5e9', '#10b981', '#f59e0b'],
    hoverBackgroundColor: ['#4f46e5', '#0284c7', '#059669', '#d97706'],
    borderWidth: 0,
    hoverOffset: 6
  }]
}))

const roleChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        usePointStyle: true,
        padding: 20,
        font: {
          size: 12
        }
      }
    },
    title: {
      display: true,
      text: 'Répartition par type d\'utilisateur',
      font: {
        size: 14,
        weight: 'bold'
      },
      padding: {
        bottom: 15
      }
    },
    tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.8)',
      padding: 12,
      cornerRadius: 8
    }
  }
}

const likeDislikeBarData = computed(() => ({
  labels: ['👍 Likes', '👎 Dislikes'],
  datasets: [{
    label: 'Réactions',
    data: [stats.value.positiveReactions ?? 0, stats.value.negativeReactions ?? 0],
    backgroundColor: ['#38bdf8', '#f43f5e'],
    hoverBackgroundColor: ['#0ea5e9', '#e11d48'],
    borderRadius: 6,
    maxBarThickness: 80
  }]
}))

const likeDislikeBarOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false },
    title: {
      display: true,
      text: 'Totaux des Réactions',
      font: {
        size: 14,
        weight: 'bold'
      },
      padding: {
        bottom: 15
      }
    },
    tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.8)',
      padding: 12,
      cornerRadius: 8
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        display: true,
        color: 'rgba(0, 0, 0, 0.05)'
      },
      ticks: {
        font: {
          size: 12
        }
      }
    },
    x: {
      grid: {
        display: false
      },
      ticks: {
        font: {
          size: 12
        }
      }
    }
  }
}

const conversationsByDayChartData = computed(() => ({
  labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
  datasets: [{
    label: 'Conversations',
    data: stats.value.dailyConversations || [],
    backgroundColor: '#8b5cf6',
    hoverBackgroundColor: '#7c3aed',
    borderRadius: 6,
    maxBarThickness: 40
  }]
}))

const barChartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: { display: false },
    title: {
      display: true,
      text: 'Conversations par jour',
      font: {
        size: 14,
        weight: 'bold'
      },
      padding: {
        bottom: 15
      }
    },
    tooltip: {
      backgroundColor: 'rgba(0, 0, 0, 0.8)',
      padding: 12,
      cornerRadius: 8
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      grid: {
        display: true,
        color: 'rgba(0, 0, 0, 0.05)'
      },
      ticks: {
        font: {
          size: 12
        }
      }
    },
    x: {
      grid: {
        display: false
      },
      ticks: {
        font: {
          size: 12
        }
      }
    }
  }
}

const getIconForStat = (statKey) => {
  const icons = {
    totalMessages: 'M8 9.5a1.5 1.5 0 100-3 1.5 1.5 0 000 3z',
    userCount: 'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z',
    averageMessagesPerUser: 'M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5',
    engagementRate: 'M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z',
    reactions: 'M7.493 18.75c-.425 0-.82-.236-.975-.632A7.48 7.48 0 016 15.375c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75 2.25 2.25 0 012.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23h-.777zM2.331 10.977a11.969 11.969 0 00-.831 4.398 12 12 0 00.52 3.507c.26.85 1.084 1.368 1.973 1.368H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 01-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227z',
    avgResponseTime: 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'
  }
  
  return icons[statKey] || 'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z'
}
</script>

<template>
 
    <div class="max-w-7xl mx-auto">
      <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          Tableau de Bord Chatbot
        </h2>
        <p class="text-gray-500">Analyse des performances et interactions utilisateurs</p>
      </div>

      <div v-if="loading" class="flex items-center justify-center h-64">
        <div class="flex flex-col items-center">
          <svg class="animate-spin h-10 w-10 text-indigo-600 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <span class="text-gray-600 font-medium">Chargement des statistiques...</span>
        </div>
      </div>

      <div v-else>
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow">
            <div class="flex items-center">
              <div class="flex-shrink-0 p-3 bg-blue-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-600">
                  <path stroke-linecap="round" stroke-linejoin="round" :d="getIconForStat('totalMessages')" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Total des messages</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ stats.totalMessages }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow">
            <div class="flex items-center">
              <div class="flex-shrink-0 p-3 bg-indigo-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-600">
                  <path stroke-linecap="round" stroke-linejoin="round" :d="getIconForStat('userCount')" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Utilisateurs uniques</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ stats.userCount }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow">
            <div class="flex items-center">
              <div class="flex-shrink-0 p-3 bg-purple-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-purple-600">
                  <path stroke-linecap="round" stroke-linejoin="round" :d="getIconForStat('averageMessagesPerUser')" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Moyenne / utilisateur</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ stats.averageMessagesPerUser }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow">
            <div class="flex items-center">
              <div class="flex-shrink-0 p-3 bg-emerald-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-emerald-600">
                  <path stroke-linecap="round" stroke-linejoin="round" :d="getIconForStat('engagementRate')" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Taux d'engagement</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ stats.engagementRate }}%</p>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow">
            <div class="flex items-center">
              <div class="flex-shrink-0 p-3 bg-amber-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-600">
                  <path stroke-linecap="round" stroke-linejoin="round" :d="getIconForStat('reactions')" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Réactions</h3>
                <div class="flex items-center mt-1 gap-3">
                  <span class="text-lg font-bold text-emerald-600">👍 {{ stats.positiveReactions }}</span>
                  <span class="text-lg font-bold text-red-500">👎 {{ stats.negativeReactions }}</span>
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow">
            <div class="flex items-center">
              <div class="flex-shrink-0 p-3 bg-sky-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-sky-600">
                  <path stroke-linecap="round" stroke-linejoin="round" :d="getIconForStat('avgResponseTime')" />
                </svg>
              </div>
              <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500">Temps de réponse moyen</h3>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ stats.avgResponseTime }}s</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-6">Répartition des Réactions</h3>
            <div class="h-64">
              <Pie :data="reactionChartData" :options="reactionChartOptions" />
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-6">Conversations par Jour</h3>
            <div class="h-64">
              <Bar :data="conversationsByDayChartData" :options="barChartOptions" />
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-6">Totaux des Réactions</h3>
            <div class="h-64">
              <Bar :data="likeDislikeBarData" :options="likeDislikeBarOptions" />
            </div>
          </div>

          <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-medium text-gray-800 mb-6 flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Répartition par type d'utilisateur
            </h3>
            <div class="h-64">
              <Pie :data="roleChartData" :options="roleChartOptions" />
            </div>
          </div>
        </div>

        <!-- Top Questions -->
      <!-- Top Questions with improved design -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
  <h3 class="text-lg font-medium text-gray-800 mb-6 flex items-center">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg>
    Top 5 des questions fréquentes
  </h3>
  
  <div class="space-y-4">
    <div v-for="(question, i) in stats.topQuestions" :key="i" 
         class="p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg hover:from-indigo-100 hover:to-purple-100 transition-all duration-300 cursor-pointer">
      <div class="flex items-center">
        <div class="h-10 w-10 flex items-center justify-center bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-full text-sm font-medium shadow-sm">
          {{ i + 1 }}
        </div>
        <div class="ml-4 flex-1">
          <p class="text-gray-700 font-medium">{{ question }}</p>
          <div class="flex items-center mt-2 text-sm text-gray-500">
            <span class="flex items-center mr-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              {{ Math.floor(Math.random() * 200) + 50 }} vues
            </span>
            <span class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
              </svg>
              {{ Math.floor(Math.random() * 30) + 5 }} réponses
            </span>
          </div>
        </div>
        <div class="flex-shrink-0">
          <button class="text-indigo-600 hover:text-indigo-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <div class="mt-6 flex justify-center">
    <button class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors font-medium text-sm flex items-center">
      Voir toutes les questions fréquentes
      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>
  </div>
</div>
      </div>
    </div>

</template>

<style scoped>
.rounded-xl {
  border-radius: 0.75rem;
}
</style>