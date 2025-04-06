<template>
    <Head title="Dashboard" />

    <AppLayout :user="user || {}">
        <div class="album-list">
            <h1>Album List</h1>

            <div class="search-container">
                <!-- Search Box and Button -->
                <div class="search-form">
                    <input
                        v-model="form.query"
                        @input="debounceSearch"
                        type="text"
                        ref="searchInput"
                        placeholder="Search by song name..."
                        class="search-input"
                    />
                    <button @click="clickSearch" :disabled="form.processing" class="search-button">Search</button>
                    <button @click="clickResetFilters" class="reset-button">Reset Filters</button>
                </div>

                <!-- Loading Spinner -->
                <div v-if="form.processing" class="loading-container">
                    <div class="loading-spinner"></div>
                </div>
            </div>

            <!-- Albums Container with Search Filter Applied -->
            <div v-if="!form.processing">
                <div v-if="state.albums.length === 0" class="no-albums">
                    <div class="no-albums-container">
                        <div class="icon">
                            <i class="fa fa-exclamation-circle"></i>
                        </div>
                        <p>No albums found</p>
                        <span>Please try again later or check the search criteria.</span>
                    </div>
                </div>

                <div class="albums-container" v-else>
                    <div v-for="album in state.albums" :key="album.id" class="album-card">
                        <img :src="album.cover_image || '/default-cover.jpg'" alt="Album Cover" class="album-image" />
                        <div class="album-info">
                            <h2>{{ album.song_name }}</h2>
                            <p>{{ album.artist_name }}</p>
                            <p><strong>Votes:</strong> {{ album.total_votes || 0 }}</p>

                            <div class="vote-container">
                                <div class="left-buttons">
                                    <button @click="clickVote('up', album.id)" class="upvote-btn" :disabled="['up'].includes(album.user_vote)">
                                        <i class="fa fa-thumbs-up"></i> {{ album.upvotes_count }}
                                    </button>
                                    <button @click="clickVote('down', album.id)" class="downvote-btn" :disabled="['down'].includes(album.user_vote)">
                                        <i class="fa fa-thumbs-down"></i> {{ album.downvotes_count }}
                                    </button>
                                </div>

                                <div class="right-buttons" v-show="is_admin">
                                    <button @click="clickDeleteAlbum(album.id)" class="delete-btn"><i class="fa fa-trash"></i> Delete album</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Load More Button -->
                <div v-if="state.current_page < state.last_page" class="load-more-container">
                    <button @click="clickLoadMore" class="load-more-button">Load More</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { onMounted, reactive, ref } from 'vue';

const { user, albums, query } = defineProps<{
    albums: {
        data: Array<{
            id: number;
            song_name: string;
            artist_name: string;
            cover_image: string | null;
            total_votes: number;
            user_vote: string;
        }>;
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    query: string | '';
    is_admin: boolean;
}>();

const searchInput = ref<HTMLInputElement | null>(null);
const form = useForm({
    query: query || '',
    page: 1,
});

const state = reactive({
    albums: [...albums.data],
    current_page: albums.current_page,
    last_page: albums.last_page,
});

onMounted(() => {
    if (searchInput.value) {
        searchInput.value.focus();
    }
});

// Start of Events
const clickSearch = async () => {
    state.albums = [];
    state.current_page = 1;
    await fetchAlbums();
};

const debounceSearch = debounce(async () => {
    state.albums = [];
    state.current_page = 1;
    await fetchAlbums();
}, 1000);

const clickLoadMore = () => {
    if (state.current_page < state.last_page) {
        state.current_page = state.current_page + 1;
        form.page = state.current_page;
        fetchAlbums();
    }
};

const clickResetFilters = () => {
    form.query = '';
    form.page = 1;
    state.current_page = 1;
    fetchAlbums();
};

const clickVote = async (type: string, album_id: number) => {
    if (type === 'up') {
        await submitUpvote(album_id);
    }
    if (type === 'down') {
        await submitDownvote(album_id);
    }
};

const clickDeleteAlbum = async (album_id: number) => {
    await submitDeleteAlbum(album_id);
};
// End of Events

// Start of Request Functions
const fetchAlbums = async () => {
    form.get(route('dashboard'), {
        preserveState: true,
        onSuccess: (data) => {
            if (state.current_page > 1) {
                state.albums = [...state.albums, ...data.props.albums.data];
            } else {
                state.albums = data.props.albums.data;
            }
        },
    });

    if (searchInput.value) {
        searchInput.value.focus();
    }
};

const submitUpvote = async (album_id: number) => {
    form.post(route('album.upvote', album_id), {
        preserveState: false,
        onSuccess: (data) => {
            alert(data.props.flash.success);
        },
    });
};

const submitDownvote = async (album_id: number) => {
    form.post(route('album.downvote', album_id), {
        preserveState: false,
        onSuccess: (data) => {
            alert(data.props.flash.success);
        },
    });
};

const submitDeleteAlbum = async (album_id: number) => {
    form.delete(route('album.destroy', album_id), {
        preserveState: false,
        onSuccess: (data) => {
            alert(data.props.flash.success || data.props.flash.error);
        },
    });
};
// End of Request Functions
</script>
<style scoped>
.no-albums span {
    color: #888;
    font-size: 16px;
    font-weight: normal;
}

.no-albums .icon {
    font-size: 50px;
    color: #ff6f61; /* A soft red color for the icon */
    margin-bottom: 15px;
}

/* Add some padding to the container to make it look spacious */
.no-albums-container {
    padding: 15px;
    max-width: 600px;
    margin: 0 auto;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.no-albums {
    text-align: center;
    font-size: 18px;
    color: #555;
    margin-top: 20px;
    font-weight: bold;
    display: flex;
    justify-content: center;
    align-items: center;
}

.album-list {
    padding: 20px;
    font-family: Arial, sans-serif;
}

h1 {
    text-align: center;
    margin-bottom: 20px;
}

.search-container {
    margin: 20px;
    padding: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.search-input {
    width: 300px;
    padding: 10px;
    border-radius: 5px 0 0 5px;
    border: 1px solid #ccc;
}

button {
    padding: 10px;
    background-color: #4caf50;
    color: white;
    border: none;
    border-radius: 5px;
    border-radius: 0 5px 5px 0;
    margin-top: 10px;
    height: 38px;
}

button:disabled {
    background-color: #ddd;
    cursor: not-allowed;
}

/* Container for loading spinner */
.loading-container {
    display: flex;
    justify-content: center;
    align-items: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    height: 100%;
}

/* Loading spinner */
.loading-spinner {
    width: 50px;
    height: 50px;
    border: 6px solid #f3f3f3;
    border-top: 6px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.albums-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* 4 per row */
    gap: 20px;
}

.album-card {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.album-card:hover {
    background: #f1f1f1;
}

.album-image {
    width: 100%;
    border-radius: 8px;
    margin-bottom: 10px;
    height: 200px;
    object-fit: cover;
}

.album-info {
    text-align: center;
}

.album-info h2 {
    font-size: 18px;
    margin: 10px 0;
}

.album-info p {
    font-size: 14px;
    color: #555;
}

/* Responsive layout */
@media screen and (max-width: 1024px) {
    .albums-container {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media screen and (max-width: 768px) {
    .albums-container {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media screen and (max-width: 480px) {
    .albums-container {
        grid-template-columns: 1fr;
    }
}

/* Load More Button Styles */
.load-more-container {
    text-align: center;
    margin-top: 20px;
}

.load-more-button {
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.load-more-button:hover {
    background-color: #0056b3;
}

/* Styling for reset button */
.reset-button {
    padding: 10px 20px;
    background-color: #ff6f61;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    margin-left: 10px;
}

.reset-button:hover {
    background-color: #e55e50;
}

/* * Upvote and Downvote Button Styles * */
.vote-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.left-buttons {
    display: flex;
    gap: 10px;
}

.right-buttons {
    margin-left: auto;
}

.upvote-btn,
.downvote-btn,
.delete-btn {
    padding: 5px 10px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    cursor: pointer;
    transition: background-color 0.3s;
}

.upvote-btn {
    background-color: #28a745;
    border: 1px solid #28a745;
}

.upvote-btn:hover {
    background-color: #218838;
}

.downvote-btn {
    background-color: #baa010;
    border: 1px solid #baa010;
}

.downvote-btn:disabled,
.upvote-btn:disabled {
    color: #2b2829;
    border: 1px solid #2b2829;
}

.downvote-btn:hover {
    background-color: #c82333;
}

.upvote-btn i,
.downvote-btn i {
    margin-right: 5px;
}

.delete-btn {
    background-color: red;
    color: white;
    border: none;
    padding: 5px 10px;
    cursor: pointer;
}

.delete-btn:hover {
    background-color: darkred;
}
</style>
