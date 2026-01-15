<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $quiz->title }} - Ujian</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        body { background-color: #0b1221; }
    </style>
</head>
<body class="min-h-screen bg-[#0b1221] text-white"
    x-data="examApp()"
    x-init="initExam()">

    {{-- Top Header --}}
    <div class="fixed top-0 left-0 right-0 bg-gradient-to-r from-[#0f172a] to-[#0b1221] border-b border-[#1e293b] z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-[#d4af37]/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white">{{ $quiz->title }}</h1>
                    <p class="text-xs text-gray-500">{{ $quiz->questions->count() }} Soal</p>
                </div>
            </div>
            
            {{-- Timer --}}
            <div class="flex items-center gap-2 bg-[#0b1221] border border-[#1e293b] rounded-xl px-4 py-2">
                <svg class="w-5 h-5" :class="timeRemaining < 300 ? 'text-red-400 animate-pulse' : 'text-[#d4af37]'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-mono text-lg font-bold" :class="timeRemaining < 300 ? 'text-red-400' : 'text-white'" x-text="formatTime(timeRemaining)"></span>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="pt-20 pb-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex gap-6">
                {{-- Left: Question Area --}}
                <div class="flex-1">
                    <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden">
                        {{-- Question Header --}}
                        <div class="p-6 border-b border-[#1e293b] flex items-center justify-between">
                            <span class="inline-flex items-center px-4 py-2 rounded-xl bg-[#d4af37]/10 text-[#d4af37] text-sm font-bold">
                                Soal <span class="ml-1" x-text="currentIndex + 1"></span> dari {{ $quiz->questions->count() }}
                            </span>
                            <div class="flex items-center gap-4">
                                {{-- Mark for Review Button --}}
                                <button type="button" @click="toggleMark(questions[currentIndex].id)"
                                    class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-bold transition-all"
                                    :class="isMarked(questions[currentIndex].id) 
                                        ? 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/50' 
                                        : 'bg-[#1e293b] text-gray-400 hover:text-yellow-400'">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                    <span x-text="isMarked(questions[currentIndex].id) ? 'Ditandai' : 'Tandai Ragu'"></span>
                                </button>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500">Dijawab:</span>
                                    <span class="text-sm font-bold text-[#d4af37]" x-text="answeredCount"></span>
                                    <span class="text-xs text-gray-500">/ {{ $quiz->questions->count() }}</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Question Content --}}
                        <div class="p-8">
                            <template x-for="(question, qIndex) in questions" :key="question.id">
                                <div x-show="currentIndex === qIndex" x-cloak>
                                    {{-- Question Text --}}
                                    <div class="mb-8">
                                        <p class="text-lg text-white leading-relaxed" x-text="question.question_text"></p>
                                        
                                        {{-- Audio Player --}}
                                        <template x-if="question.audio_url">
                                            <div class="mt-4 bg-[#1e293b] rounded-xl p-4">
                                                <div class="flex items-center gap-3">
                                                    <button type="button" @click="playAudio(question.id)"
                                                        class="h-12 w-12 rounded-full bg-[#d4af37] flex items-center justify-center hover:bg-[#b8962e] transition-all">
                                                        <svg x-show="!isPlaying[question.id]" class="w-6 h-6 text-[#0b1221] ml-1" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M8 5v14l11-7z"/>
                                                        </svg>
                                                        <svg x-show="isPlaying[question.id]" class="w-6 h-6 text-[#0b1221]" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                                                        </svg>
                                                    </button>
                                                    <div class="flex-1">
                                                        <p class="text-sm text-gray-400 mb-1">🎧 Audio Listening</p>
                                                        <div class="bg-[#0b1221] rounded-full h-2 overflow-hidden">
                                                            <div class="bg-[#d4af37] h-full transition-all" :style="'width: ' + (audioProgress[question.id] || 0) + '%'"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <audio :id="'audio_' + question.id" :src="'/storage/' + question.audio_url" 
                                                    @timeupdate="updateAudioProgress(question.id, $event)"
                                                    @ended="isPlaying[question.id] = false"
                                                    class="hidden"></audio>
                                            </div>
                                        </template>

                                        {{-- Image --}}
                                        <template x-if="question.image_url">
                                            <img :src="'/storage/' + question.image_url" class="mt-4 max-w-full max-h-64 rounded-xl border border-[#1e293b]" alt="Gambar Soal">
                                        </template>
                                    </div>
                                    
                                    {{-- Options --}}
                                    <div class="space-y-3">
                                        <template x-for="(option, oIndex) in question.options" :key="option.id">
                                            <label class="flex items-center gap-4 p-4 rounded-xl border cursor-pointer transition-all duration-200"
                                                :class="answers[question.id] == option.id 
                                                    ? 'bg-[#d4af37]/10 border-[#d4af37] text-white' 
                                                    : 'bg-[#0b1221] border-[#1e293b] text-gray-300 hover:border-[#d4af37]/50'">
                                                <input type="radio" 
                                                    :name="'question_' + question.id" 
                                                    :value="option.id"
                                                    x-model="answers[question.id]"
                                                    @change="saveProgress()"
                                                    class="w-5 h-5 border-[#1e293b] bg-[#0f172a] text-[#d4af37] focus:ring-[#d4af37]/50">
                                                <span class="flex-shrink-0 h-8 w-8 rounded-lg flex items-center justify-center text-sm font-bold"
                                                    :class="answers[question.id] == option.id ? 'bg-[#d4af37] text-[#0b1221]' : 'bg-[#1e293b] text-gray-400'"
                                                    x-text="String.fromCharCode(65 + oIndex)"></span>
                                                <span class="flex-1" x-text="option.option_text"></span>
                                            </label>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        {{-- Navigation Buttons --}}
                        <div class="p-6 border-t border-[#1e293b] flex items-center justify-between">
                            <button type="button" @click="prevQuestion()" 
                                :disabled="currentIndex === 0"
                                :class="currentIndex === 0 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-[#334155]'"
                                class="inline-flex items-center px-6 py-3 rounded-xl bg-[#1e293b] text-gray-300 transition-all text-sm font-bold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                SEBELUMNYA
                            </button>
                            
                            <template x-if="currentIndex < questions.length - 1">
                                <button type="button" @click="nextQuestion()"
                                    class="inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                                    SELANJUTNYA
                                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>
                
                {{-- Right: Navigator Sidebar --}}
                <div class="w-80 flex-shrink-0">
                    <div class="sticky top-24 space-y-4">
                        {{-- Navigator Box --}}
                        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl overflow-hidden">
                            <div class="p-4 border-b border-[#1e293b]">
                                <h3 class="text-sm font-bold text-white">Navigator Soal</h3>
                            </div>
                            <div class="p-4">
                                <div class="grid grid-cols-5 gap-2">
                                    <template x-for="(question, qIndex) in questions" :key="question.id">
                                        <button type="button" @click="goToQuestion(qIndex)"
                                            class="h-10 w-10 rounded-lg flex items-center justify-center text-sm font-bold transition-all"
                                            :class="getNavigatorClass(qIndex, question.id)">
                                            <span x-text="qIndex + 1"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            <div class="p-4 border-t border-[#1e293b] space-y-2">
                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                    <span class="h-4 w-4 rounded bg-[#d4af37] border border-[#d4af37]"></span>
                                    <span>Soal Sekarang</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                    <span class="h-4 w-4 rounded bg-green-500/20 border border-green-500"></span>
                                    <span>Sudah Dijawab</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                    <span class="h-4 w-4 rounded bg-yellow-500/20 border border-yellow-500"></span>
                                    <span>Ditandai Ragu-ragu</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-gray-400">
                                    <span class="h-4 w-4 rounded bg-[#1e293b] border border-[#334155]"></span>
                                    <span>Belum Dijawab</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Submit Button --}}
                        <button type="button" @click="confirmSubmit()"
                            class="w-full inline-flex justify-center items-center px-6 py-4 rounded-xl bg-gradient-to-r from-green-600 to-green-700 text-white hover:from-green-700 hover:to-green-800 transition-all text-sm font-bold shadow-lg shadow-green-500/20">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            SELESAI & KUMPULKAN
                        </button>
                        
                        <p class="text-xs text-gray-500 text-center">
                            Pastikan semua soal sudah dijawab sebelum mengumpulkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Submit Confirmation Modal --}}
    <div x-show="showConfirmModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-[#1e293b] rounded-2xl w-full max-w-md mx-4 shadow-2xl"
            @click.away="showConfirmModal = false">
            <div class="p-6 border-b border-[#1e293b]">
                <h3 class="text-lg font-bold text-white">Konfirmasi Pengumpulan</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-300 mb-4">Apakah Anda yakin ingin mengumpulkan jawaban?</p>
                <div class="bg-[#0b1221] border border-[#1e293b] rounded-xl p-4 mb-4">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Soal Dijawab</span>
                        <span class="font-bold" :class="answeredCount === questions.length ? 'text-green-400' : 'text-yellow-400'" x-text="answeredCount + '/' + questions.length"></span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-2">
                        <span class="text-gray-400">Belum Dijawab</span>
                        <span class="font-bold" :class="unansweredCount > 0 ? 'text-red-400' : 'text-green-400'" x-text="unansweredCount"></span>
                    </div>
                    <div class="flex items-center justify-between text-sm mt-2">
                        <span class="text-gray-400">Ditandai Ragu</span>
                        <span class="font-bold text-yellow-400" x-text="markedCount"></span>
                    </div>
                </div>
                <p x-show="unansweredCount > 0" class="text-yellow-400 text-sm mb-4">
                    ⚠️ Masih ada <span x-text="unansweredCount"></span> soal yang belum dijawab!
                </p>
            </div>
            <div class="p-6 border-t border-[#1e293b] flex gap-3">
                <button type="button" @click="showConfirmModal = false"
                    class="flex-1 px-4 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:bg-[#d4af37] hover:text-[#0b1221] transition-all text-sm font-bold">
                    KEMBALI
                </button>
                <button type="button" @click="submitExam()"
                    class="flex-1 px-4 py-3 rounded-xl bg-green-600 text-white hover:bg-green-700 transition-all text-sm font-bold">
                    YA, KUMPULKAN
                </button>
            </div>
        </div>
    </div>

    {{-- Time Up Modal --}}
    <div x-show="showTimeUpModal" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="bg-gradient-to-b from-[#0f172a] to-[#0b1221] border border-red-500/50 rounded-2xl w-full max-w-md mx-4 shadow-2xl">
            <div class="p-8 text-center">
                <div class="h-20 w-20 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Waktu Habis!</h3>
                <p class="text-gray-400 mb-6">Waktu ujian Anda telah habis. Jawaban akan dikumpulkan otomatis.</p>
                <button type="button" @click="submitExam()"
                    class="w-full inline-flex justify-center items-center px-6 py-3 rounded-xl border border-[#d4af37] text-[#d4af37] hover:text-white hover:bg-[#d4af37] hover:border-[#d4af37] transition-all duration-200 text-sm font-bold">
                    LIHAT HASIL
                </button>
            </div>
        </div>
    </div>

    {{-- Hidden Form for Submission --}}
    <form id="examForm" action="{{ route('student.exam.submit', $quiz) }}" method="POST" class="hidden">
        @csrf
        <template x-for="(value, key) in answers" :key="key">
            <input type="hidden" :name="'answers[' + key + ']'" :value="value">
        </template>
    </form>

    <script>
        function examApp() {
            const quizId = {{ $quiz->id }};
            const storageKey = 'exam_' + quizId;
            
            return {
                questions: @json($questionsJson),
                answers: @json($attempt->answers ?? []),
                markedForReview: @json($attempt->marked_for_review ?? []),
                currentIndex: 0,
                timeRemaining: {{ $remainingSeconds }},
                timerInterval: null,
                showConfirmModal: false,
                showTimeUpModal: false,
                isSubmitting: false,
                isPlaying: {},
                audioProgress: {},
                
                get answeredCount() {
                    return Object.keys(this.answers).filter(k => this.answers[k] !== null && this.answers[k] !== '').length;
                },
                
                get unansweredCount() {
                    return this.questions.length - this.answeredCount;
                },
                
                get markedCount() {
                    return this.markedForReview.length;
                },
                
                initExam() {
                    // Try to restore from localStorage
                    const saved = localStorage.getItem(storageKey);
                    if (saved) {
                        try {
                            const data = JSON.parse(saved);
                            // Only restore if we have a valid session
                            if (data.answers) {
                                // Merge with server data (server takes precedence for answers)
                                for (let key in data.answers) {
                                    if (!this.answers[key]) {
                                        this.answers[key] = data.answers[key];
                                    }
                                }
                            }
                            if (data.markedForReview) {
                                this.markedForReview = data.markedForReview;
                            }
                        } catch (e) {
                            console.error('Failed to restore state:', e);
                        }
                    }
                    
                    // Start timer
                    this.startTimer();
                    
                    // Warn before leaving (only when quiz is in progress)
                    this.handleBeforeUnload = (e) => {
                        if (!this.isSubmitting) {
                            this.saveToLocalStorage();
                            e.preventDefault();
                            e.returnValue = 'Ujian sedang berlangsung. Yakin ingin keluar?';
                        }
                    };
                    window.addEventListener('beforeunload', this.handleBeforeUnload);
                },
                
                saveToLocalStorage() {
                    const data = {
                        answers: this.answers,
                        markedForReview: this.markedForReview,
                        savedAt: new Date().toISOString()
                    };
                    localStorage.setItem(storageKey, JSON.stringify(data));
                },
                
                startTimer() {
                    this.timerInterval = setInterval(() => {
                        if (this.timeRemaining > 0) {
                            this.timeRemaining--;
                            // Save to localStorage every 10 seconds
                            if (this.timeRemaining % 10 === 0) {
                                this.saveToLocalStorage();
                            }
                        } else {
                            clearInterval(this.timerInterval);
                            this.showTimeUpModal = true;
                        }
                    }, 1000);
                },
                
                formatTime(seconds) {
                    const m = Math.floor(seconds / 60);
                    const s = seconds % 60;
                    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                },
                
                goToQuestion(index) {
                    this.currentIndex = index;
                },
                
                nextQuestion() {
                    if (this.currentIndex < this.questions.length - 1) {
                        this.currentIndex++;
                    }
                },
                
                prevQuestion() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                    }
                },
                
                isMarked(questionId) {
                    return this.markedForReview.includes(questionId);
                },
                
                toggleMark(questionId) {
                    const index = this.markedForReview.indexOf(questionId);
                    if (index > -1) {
                        this.markedForReview.splice(index, 1);
                    } else {
                        this.markedForReview.push(questionId);
                    }
                    this.saveProgress();
                },
                
                getNavigatorClass(index, questionId) {
                    if (index === this.currentIndex) {
                        return 'bg-[#d4af37] text-[#0b1221] border-2 border-[#d4af37]';
                    }
                    if (this.isMarked(questionId)) {
                        return 'bg-yellow-500/20 text-yellow-400 border border-yellow-500 hover:bg-yellow-500/30';
                    }
                    if (this.answers[questionId]) {
                        return 'bg-green-500/20 text-green-400 border border-green-500 hover:bg-green-500/30';
                    }
                    return 'bg-[#1e293b] text-gray-400 border border-[#334155] hover:border-[#d4af37]/50';
                },
                
                playAudio(questionId) {
                    const audio = document.getElementById('audio_' + questionId);
                    if (audio) {
                        if (this.isPlaying[questionId]) {
                            audio.pause();
                            this.isPlaying[questionId] = false;
                        } else {
                            // Pause all other audios
                            this.questions.forEach(q => {
                                if (q.audio_url && q.id !== questionId) {
                                    const otherAudio = document.getElementById('audio_' + q.id);
                                    if (otherAudio) otherAudio.pause();
                                    this.isPlaying[q.id] = false;
                                }
                            });
                            audio.play();
                            this.isPlaying[questionId] = true;
                        }
                    }
                },
                
                updateAudioProgress(questionId, event) {
                    const audio = event.target;
                    if (audio.duration) {
                        this.audioProgress[questionId] = (audio.currentTime / audio.duration) * 100;
                    }
                },
                
                async saveProgress() {
                    this.saveToLocalStorage();
                    
                    try {
                        await fetch('{{ route("student.exam.save", $attempt) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ 
                                answers: this.answers,
                                marked_for_review: this.markedForReview
                            })
                        });
                    } catch (e) {
                        console.error('Failed to save progress:', e);
                    }
                },
                
                confirmSubmit() {
                    this.showConfirmModal = true;
                },
                
                submitExam() {
                    this.isSubmitting = true;
                    clearInterval(this.timerInterval);
                    localStorage.removeItem(storageKey);
                    // Remove the beforeunload listener to prevent popup on submit
                    window.removeEventListener('beforeunload', this.handleBeforeUnload);
                    document.getElementById('examForm').submit();
                }
            }
        }
    </script>
</body>
</html>
