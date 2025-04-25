// resources/js/constants/menuItems.js

export const adminMenuItems = [
    { name: 'Dashboard', route: 'admin.dashboard', icon: 'bx bx-home' },
    { name: 'Messages Support', route: 'admin.support.messages', icon: 'bx bx-envelope' },
    { name: 'FAQs', route: 'admin.faqs.index', icon: 'bx bx-question-mark' },
    { name: 'Chatbot IA', route: 'admin.chatbot.index', icon: 'bx bx-bot' },
    { name: 'Réactions Chatbot', route: 'admin.chatbot.reactions', icon: 'bx bx-like' }
  ]
  
  export const userMenuItems = [
    { name: 'Dashboard', route: 'dashboard', icon: 'bx bx-home' },
    { name: 'Profil', route: 'profile.edit', icon: 'bx bx-user' }
  ]
  
  // Utilitaire pour titre
  export const pageTitles = [
    ...adminMenuItems,
    ...userMenuItems
  ].reduce((acc, item) => {
    acc[item.route] = item.name
    return acc
  }, {})
  