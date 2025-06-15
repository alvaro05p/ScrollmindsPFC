<template>
    <div ref="scrollContainer" class="video-feed" @scroll="handleScroll">
        <!-- Botón para activar sonido global -->
        <button class="activar-sonido" @click="activarSonidoGlobal">
            🔊 Activar sonido
        </button>

        <!-- Lista de videos -->
        <div v-for="video in videos" :key="video.id" class="video-item">
            <div class="video-wrapper">
                <video
                    :ref="el => registerVideoRef(video.id, el)"
                    :src="`/${video.ruta_video}`"
                    class="video-player"
                    loop
                    muted
                    playsinline
                    @click="togglePlaySound($event)"
                />

                <!-- Botón de sonido -->
                <button
                    class="sound-btn position-absolute top-0 start-0 m-2 btn btn-sm btn-light"
                    @click="toggleSound(video.id)"
                >
                    🔊
                </button>

                <!-- Overlay con detalles y botones -->
                <div class="video-overlay">
                    <div class="video-actions">
                        <button
                            @click="likeVideo(video.id)"
                            class="action-btn"
                            :class="{ liked: video.user_has_liked }"
                        >
                            <span class="action-icon">
                                {{ video.user_has_liked ? "❤️" : "❤️" }}
                            </span>
                            <span class="action-count">{{ video.likes }}</span>
                        </button>

                        <button
                            v-if="video.test && video.test.preguntas.length"
                            class="action-btn"
                            @click="showTest(video.test)"
                        >
                            <span class="action-icon">📝</span>
                            <span class="action-text">Test</span>
                        </button>
                    </div>

                    <div class="video-details">
                        <h5 class="mb-1">{{ video.titulo }}</h5>
                        <p class="mb-2 video-description">
                            {{ video.descripcion }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loader -->
        <div v-if="loading" class="loader-container">
            <div class="spinner"></div>
        </div>

        <!-- Modal de Test -->
        <div v-if="showTestModal" class="custom-modal">
            <div class="custom-modal-backdrop" @click="hideTestModal"></div>
            <div class="custom-modal-content">
                <div class="custom-modal-header">
                    <h5>Test Rápido</h5>
                    <button type="button" class="close-button" @click="hideTestModal">×</button>
                </div>
                <div class="custom-modal-body">
                    <div v-if="currentTest">
                        <div class="test-question">
                            <h6>{{ currentQuestion.texto_pregunta }}</h6>
                            <div v-if="currentQuestion.opciones" class="options-list">
                                <div
                                    v-for="(opcion, index) in getOpciones(currentQuestion)"
                                    :key="index"
                                    class="option-item"
                                    :class="{ selected: selectedAnswer === index }"
                                    @click="evaluarRespuesta(index)"
                                    :style="{
                                        pointerEvents:
                                            testFinalizado || selectedAnswer !== null ? 'none' : 'auto',
                                        opacity:
                                            testFinalizado || selectedAnswer !== null ? 0.6 : 1,
                                    }"
                                >
                                    <div class="option-content">
                                        <div class="option-marker">{{ ['A', 'B', 'C', 'D'][index] }}</div>
                                        <div class="option-text">{{ opcion }}</div>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="selectedAnswer !== null"
                                class="answer-feedback"
                                :class="{
                                    correct: respuestasCorrectas[currentQuestion.id],
                                    incorrect: !respuestasCorrectas[currentQuestion.id],
                                }"
                            >
                                <span v-if="respuestasCorrectas[currentQuestion.id]">✅ ¡Respuesta correcta!</span>
                                <span v-else>❌ Respuesta incorrecta.</span>
                            </div>
                        </div>

                        <div class="progress-bar-container">
                            <div
                                class="progress-bar-fill"
                                :style="`width: ${
                                    ((currentQuestionIndex + 1) * 100) /
                                    currentTest.preguntas.length
                                }%`"
                            ></div>
                        </div>

                        <div class="test-navigation">
                            <button class="btn-secondary" :disabled="currentQuestionIndex === 0" @click="prevQuestion">
                                Anterior
                            </button>
                            <span>{{ currentQuestionIndex + 1 }} / {{ currentTest.preguntas.length }}</span>
                            <button class="btn-primary" @click="nextQuestion">
                                {{ currentQuestionIndex === currentTest.preguntas.length - 1 ? "Finalizar" : "Siguiente" }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

let observer = null;

export default {
    data() {
        return {
            videos: [],
            loading: false,
            page: 1,
            currentTest: null,
            currentQuestionIndex: 0,
            selectedAnswer: null,
            showTestModal: false,
            userAnswers: {},
            respuestasCorrectas: {},
            testFinalizado: false,
            videoRefs: {},
        };
    },
    computed: {
        currentQuestion() {
            return this.currentTest?.preguntas[this.currentQuestionIndex] || {};
        },
    },
    methods: {
        async loadMore() {
            if (this.loading) return;
            this.loading = true;

            try {
                const response = await axios.get("/api/videos-with-test", {
                    params: { page: this.page },
                });
                this.videos = [...this.videos, ...response.data.data];
                this.page++;
                this.$nextTick(this.inicializarObservador);
            } catch (error) {
                console.error("Error al cargar videos:", error);
            } finally {
                this.loading = false;
            }
        },
        registerVideoRef(videoId, el) {
            if (el) {
                this.videoRefs[videoId] = el;
            }
        },


        inicializarObservador() {
            if (observer) observer.disconnect();

            const options = {
                root: this.$refs.scrollContainer,
                threshold: 0.5,
            };

            observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    const video = entry.target;
                    if (entry.isIntersecting) {
                        // Pausar todos los demás
                        this.$el.querySelectorAll("video.video-player").forEach((v) => {
                            if (v !== video) {
                                v.pause();
                                v.currentTime = 0;
                            }
                        });
                        video.play().catch((err) => {
                            console.log("Autoplay bloqueado:", err);
                        });
                    } else {
                        video.pause();
                        video.currentTime = 0;
                    }
                });
            }, options);

            this.$el.querySelectorAll("video.video-player").forEach((video) => {
                observer.observe(video);
            });
        },

        toggleSound(videoId) {
            const video = this.videoRefs[videoId];
            if (!video) return;

            video.muted = !video.muted;
            if (!video.paused) {
                video.play().catch(() => {});
            }
        },

        activarSonidoGlobal() {
            Object.values(this.videoRefs).forEach((video) => {
                if (video) {
                    video.muted = false;

                    // Forzar la reproducción sólo si está en viewport
                    const rect = video.getBoundingClientRect();
                    const inViewport =
                        rect.top >= 0 &&
                        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight);

                    if (inViewport) {
                        video.play().catch((error) => {
                            console.warn("Error al intentar reproducir video con sonido:", error);
                        });
                    }
                }
            });
        },

        likeVideo(videoId) {
            axios.post(`/videos/${videoId}/like`).then((response) => {
                const video = this.videos.find((v) => v.id === videoId);
                if (video) {
                    video.likes = response.data.likes;
                    video.user_has_liked = response.data.liked;
                }
            }).catch((error) => {
                if (error.response?.status === 401) {
                    alert("Debes iniciar sesión para dar like");
                } else {
                    console.error("Error al dar like:", error);
                }
            });
        },

        handleScroll() {
            const container = this.$refs.scrollContainer;
            if (!container) return;

            const scrollBottom = container.scrollTop + container.clientHeight;
            if (scrollBottom + 100 >= container.scrollHeight) {
                this.loadMore();
            }
        },

        showTest(test) {
            this.currentTest = test;
            this.currentQuestionIndex = 0;
            this.selectedAnswer = this.userAnswers[test.preguntas[0]?.id] ?? null;
            this.showTestModal = true;
            document.body.style.overflow = "hidden";
        },

        hideTestModal() {
            this.showTestModal = false;
            this.currentTest = null;
            this.userAnswers = {};
            this.respuestasCorrectas = {};
            this.testFinalizado = false;
            this.selectedAnswer = null;
            this.currentQuestionIndex = 0;
            document.body.style.overflow = "";
        },

        getOpciones(pregunta) {
            if (!pregunta.opciones) return [];
            return typeof pregunta.opciones === "string"
                ? JSON.parse(pregunta.opciones)
                : pregunta.opciones;
        },

        nextQuestion() {
            const letras = ["A", "B", "C", "D"];
            if (this.selectedAnswer !== null) {
                const letra = letras[this.selectedAnswer];
                this.userAnswers[this.currentQuestion.id] = letra;
                this.respuestasCorrectas[this.currentQuestion.id] =
                    letra === this.currentQuestion.opcion_correcta.toUpperCase();
            }

            if (this.currentQuestionIndex < this.currentTest.preguntas.length - 1) {
                this.currentQuestionIndex++;
                const saved = this.userAnswers[this.currentTest.preguntas[this.currentQuestionIndex].id];
                this.selectedAnswer = saved ? letras.indexOf(saved) : null;
            } else {
                this.submitTest();
            }
        },

        prevQuestion() {
            if (this.currentQuestionIndex > 0) {
                const letras = ["A", "B", "C", "D"];
                this.userAnswers[this.currentQuestion.id] = letras[this.selectedAnswer];
                this.currentQuestionIndex--;
                const saved = this.userAnswers[this.currentTest.preguntas[this.currentQuestionIndex].id];
                this.selectedAnswer = saved ? letras.indexOf(saved) : null;
            }
        },

        evaluarRespuesta(index) {
            const letras = ["A", "B", "C", "D"];
            const letra = letras[index];
            this.selectedAnswer = index;
            this.userAnswers[this.currentQuestion.id] = letra;
            this.respuestasCorrectas[this.currentQuestion.id] =
                letra === this.currentQuestion.opcion_correcta.toUpperCase();
        },

        async submitTest() {
            try {
                const testId = this.currentTest.id;
                await axios.post(
                    `/api/tests/${testId}/submit`,
                    { respuestas: this.userAnswers },
                    {
                        withCredentials: true,
                        headers: {
                            "X-Requested-With": "XMLHttpRequest",
                            "Accept": "application/json",
                            "Content-Type": "application/json",
                        },
                    }
                );
                this.testFinalizado = true;
                alert("Test enviado correctamente!");
                this.hideTestModal();
            } catch (error) {
                if (error.response?.status === 401) {
                    alert("No estás autenticado. Por favor inicia sesión.");
                    window.location.href = "/login";
                } else if (error.response?.status === 422) {
                    alert("Error en los datos enviados. Revisa tus respuestas.");
                } else {
                    alert("Error de conexión. Inténtalo de nuevo.");
                }
            }
        },
    },

    mounted() {
        this.loadMore();
    },
};
</script>
