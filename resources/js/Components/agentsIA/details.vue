<template>
    <div class="row">
        <div class="col-12 mb-5">
            <div class="card card-1 cardDetailsAgent">
                <div class="card-body">
                    <img :src="agent.image" :alt="agent.name" class="image">
                    <div>
                        <div class="title d-flex justify-content-between align-items-start">
                            <h5>{{ agent.greeting }}</h5>
                            <div class="d-flex align-items-center">
                                <img src="/assets/img/dash/close.png" alt="">
                                <h4>{{ agent.type }}</h4>
                            </div>
                        </div>
                        <h6>{{ agent.role }}</h6>
                        <p>{{ agent.description }}</p>
                        <div class="tags d-flex flex-wrap g-2">
                            <span v-for="tag in agent.tags" :key="tag">#{{ tag }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 mb-5">
            <div class="card card-1 cardTimeline">
                <div class="card-header">
                    <h5>Responsabilité</h5>
                </div>
                <div class="card-body">
                    <ul class="timeline mb-0">
                        <li v-for="(responsibility, index) in responsibilities" :key="index"
                            class="timeline-item timeline-item-transparent">
                            <span class="timeline-point timeline-point-primary"></span>
                            <div class="timeline-event p-3">
                                <div class="timeline-heet on the flightader mb-3">
                                    <h6 class="mb-0">{{ responsibility.title }}</h6>
                                </div>
                                <div class="content">
                                    <ul>
                                        <li v-for="(task, taskIndex) in responsibility.tasks" :key="taskIndex">
                                            <img src="/assets/img/dash/fleche.png" alt="">
                                            {{ task }}
                                        </li>
                                    </ul>
                                    <button class="btn btn-primary fs-sm">Entrainer d'avantage</button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!--chat-->
        <div class="col-12 mb-5">
            <div class="card card-1 cardChat">
                <div class="card-header pb-0 pt-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Chat avec Growthy</h5>
                </div>
                <div class="card-body p-0">
                    <div class="chat-container" ref="chatContainer">
                        <div class="chat-messages p-3">
                            <div v-for="(message, index) in messages" :key="index"
                                :class="['message', message.sender === 'user' ? 'user-message' : 'bot-message']">
                                <div class="message-content">
                                    <div class="message-text">{{ message.text }}</div>
                                    <div class="message-time">{{ message.time }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-input p-3">
                        <!-- Preview des fichiers -->
                        <div v-if="selectedFiles.length > 0" class="file-previews mt-2">
                            <div v-for="(file, index) in selectedFiles" :key="index" class="file-preview">
                                <div v-if="file.type === 'image'" class="image-preview">
                                    <img :src="file.preview" :alt="file.name" class="preview-image">
                                    <button @click="removeFile(index)" class="remove-file">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div v-else class="document-preview">
                                    <i class="ti ti-file"></i>
                                    <span>{{ file.name }}</span>
                                    <button @click="removeFile(index)" class="btn remove-file">
                                        <i class="ti ti-x"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="dropdown-2">
                                <button class="btn btn-rect" data-bs-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 28 28"
                                        fill="none">
                                        <path d="M14 1.5V26.5M1.5 14H26.5" stroke="#0093EE" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <input type="file" id="doc" class="d-none" accept=".pdf,.doc,.docx"
                                            @change="handleFileUpload($event, 'doc')">
                                        <label for="doc" class="dropdown-item text-start">
                                            <i class="ti ti-file-plus"></i>
                                            Ajouter un document
                                        </label>
                                    </li>
                                    <li>
                                        <input type="file" id="photo" class="d-none" accept="image/*"
                                            @change="handleFileUpload($event, 'photo')">
                                        <label for="photo" class="dropdown-item text-start">
                                            <i class="ti ti-photo-plus"></i>
                                            Ajouter une image
                                        </label>
                                    </li>
                                </ul>
                            </div>

                            <input type="text" v-model="newMessage" @keyup.enter="sendMessage" class="form-control"
                                placeholder="Tapez votre message..." :disabled="isTyping">
                            <button class="btn btn-rect primary me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="30" viewBox="0 0 26 30"
                                    fill="none">
                                    <path
                                        d="M13 4.4668C13.3402 4.46684 13.6685 4.59173 13.9227 4.81778C14.177 5.04383 14.3394 5.35532 14.3792 5.69316L14.3889 5.85566V28.0774C14.3885 28.4314 14.2529 28.7719 14.0099 29.0293C13.7669 29.2867 13.4348 29.4416 13.0814 29.4624C12.728 29.4831 12.38 29.3681 12.1086 29.1409C11.8371 28.9137 11.6626 28.5914 11.6208 28.2399L11.6111 28.0774V5.85566C11.6111 5.48731 11.7574 5.13405 12.0179 4.87358C12.2784 4.61312 12.6316 4.4668 13 4.4668ZM7.44444 8.63338C7.8128 8.63338 8.16607 8.7797 8.42654 9.04017C8.687 9.30063 8.83333 9.65389 8.83333 10.0222V23.9108C8.83333 24.2792 8.687 24.6325 8.42654 24.8929C8.16607 25.1534 7.8128 25.2997 7.44444 25.2997C7.07609 25.2997 6.72282 25.1534 6.46235 24.8929C6.20188 24.6325 6.05556 24.2792 6.05556 23.9108V10.0222C6.05556 9.65389 6.20188 9.30063 6.46235 9.04017C6.72282 8.7797 7.07609 8.63338 7.44444 8.63338ZM18.5556 8.63338C18.9239 8.63338 19.2772 8.7797 19.5376 9.04017C19.7981 9.30063 19.9444 9.65389 19.9444 10.0222V23.9108C19.9444 24.2792 19.7981 24.6325 19.5376 24.8929C19.2772 25.1534 18.9239 25.2997 18.5556 25.2997C18.1872 25.2997 17.8339 25.1534 17.5735 24.8929C17.313 24.6325 17.1667 24.2792 17.1667 23.9108V10.0222C17.1667 9.65389 17.313 9.30063 17.5735 9.04017C17.8339 8.7797 18.1872 8.63338 18.5556 8.63338ZM1.88889 12.8C2.25725 12.8 2.61051 12.9463 2.87098 13.2067C3.13145 13.4672 3.27778 13.8205 3.27778 14.1888V19.7443C3.27778 20.1126 3.13145 20.4659 2.87098 20.7263C2.61051 20.9868 2.25725 21.1331 1.88889 21.1331C1.52053 21.1331 1.16726 20.9868 0.906796 20.7263C0.646329 20.4659 0.5 20.1126 0.5 19.7443V14.1888C0.5 13.8205 0.646329 13.4672 0.906796 13.2067C1.16726 12.9463 1.52053 12.8 1.88889 12.8ZM24.1111 12.8C24.4513 12.8 24.7796 12.9249 25.0338 13.1509C25.2881 13.377 25.4505 13.6885 25.4903 14.0263L25.5 14.1888V19.7443C25.4996 20.0983 25.3641 20.4387 25.121 20.6961C24.878 20.9535 24.5459 21.1084 24.1925 21.1292C23.8391 21.1499 23.4911 21.035 23.2197 20.8078C22.9482 20.5806 22.7738 20.2583 22.7319 19.9068L22.7222 19.7443V14.1888C22.7222 13.8205 22.8685 13.4672 23.129 13.2067C23.3895 12.9463 23.7428 12.8 24.1111 12.8Z"
                                        fill="white" />
                                </svg>
                            </button>
                            <button class="btn btn-rect primary" @click="sendMessage"
                                :disabled="isTyping || !newMessage.trim()">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
import 'bootstrap';
import '@popperjs/core';

export default {
    name: 'detailsAgentsIA',
    data() {
        return {
            agent: {
                name: 'Growthy',
                image: '/assets/img/dash/robot_smile_2.png',
                greeting: 'Bonjour! Je suis Growthy',
                type: 'Agent IA privé',
                role: 'Assistant en campagnes publicitaires',
                description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Lorem sit consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.Lorem.',
                tags: ['Publicité', 'IA', 'Campagne']
            },
            responsibilities: [
                {
                    title: 'Optimisation des campagnes publicitaires',
                    tasks: [
                        'Analyser les performances des publicités (Google Ads, Facebook Ads, LinkedIn Ads, etc.).',
                        'Proposer des recommandations pour améliorer le retour sur investissement (ROI).',
                        'Ajuster automatiquement les budgets et audiences selon les résultats.'
                    ]
                },
                {
                    title: 'Génération et qualification de leads',
                    tasks: [
                        'Identifier les prospects les plus qualifiés via l\'analyse des interactions et des comportements en ligne',
                        'Automatiser l\'envoi de messages personnalisés pour convertir les visiteurs en clients',
                        'Intégrer les leads qualifiés directement dans le CRM'
                    ]
                },
                {
                    title: 'Veille et recommandations en marketing digital',
                    tasks: [
                        'Suivre les tendances et opportunités du marché pour adapter les stratégies de croissance.',
                        'Fournir des insights sur les mots-clés, les tendances SEO et les meilleures pratiques publicitaires.',
                        'Alerter sur les performances des campagnes et proposer des ajustements en temps réel.'
                    ]
                }
            ],
            messages: [
                {
                    text: 'Bonjour ! Comment puis-je vous aider aujourd\'hui ?',
                    sender: 'bot',
                    time: '10:00'
                }
            ],
            newMessage: '',
            isTyping: false,
            selectedFiles: []
        };
    },
    methods: {
        handleFileUpload(event, type) {
            const files = event.target.files;
            if (!files.length) return;

            Array.from(files).forEach(file => {
                if (type === 'photo' && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.selectedFiles.push({
                            file: file,
                            name: file.name,
                            type: 'image',
                            preview: e.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                } else if (type === 'doc' && (file.type === 'application/pdf' ||
                    file.type === 'application/msword' ||
                    file.type === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document')) {
                    this.selectedFiles.push({
                        file: file,
                        name: file.name,
                        type: 'document'
                    });
                }
            });
            event.target.value = ''; // Réinitialiser l'input
        },
        removeFile(index) {
            this.selectedFiles.splice(index, 1);
        },
        sendMessage() {
            if (!this.newMessage.trim() && this.selectedFiles.length === 0) return;

            // Ajouter le message de l'utilisateur
            this.messages.push({
                text: this.newMessage,
                sender: 'user',
                time: this.getCurrentTime(),
                files: [...this.selectedFiles]
            });

            // Simuler une réponse du bot
            this.isTyping = true;
            setTimeout(() => {
                this.messages.push({
                    text: 'Je suis désolé, je ne peux pas répondre pour le moment. Cette fonctionnalité sera bientôt disponible !',
                    sender: 'bot',
                    time: this.getCurrentTime()
                });
                this.isTyping = false;
            }, 1000);

            this.newMessage = '';
            this.selectedFiles = [];
            this.$nextTick(() => {
                this.scrollToBottom();
            });
        },
        getCurrentTime() {
            const now = new Date();
            return now.getHours().toString().padStart(2, '0') + ':' +
                now.getMinutes().toString().padStart(2, '0');
        },
        scrollToBottom() {
            const container = this.$refs.chatContainer;
            container.scrollTop = container.scrollHeight;
        }
    },
    mounted() {
        this.scrollToBottom();
    }
};
</script>
