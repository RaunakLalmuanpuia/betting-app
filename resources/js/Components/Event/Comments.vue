<template>
    <div class="bg-white text-gray-900 min-h-screen">
        <main class="max-w-3xl mx-auto p-6">
            <!-- Header -->
            <header class="flex items-center space-x-4 mb-6">
                <p class="font-extrabold text-2xl">Ideas</p>
                <p class="font-extrabold text-2xl text-gray-400 select-none px-4">Activity</p>
            </header>

            <!-- Post Form -->
            <form class="mb-10">
                <div class="border border-gray-200 rounded-lg p-4">
                    <textarea
              class="w-full resize-none placeholder-gray-400 text-gray-900 text-base focus:outline-none"
              maxlength="800"
              placeholder="What’s your prediction?"
              rows="2"
          ></textarea>
                    <div class="flex justify-end items-center mt-2 space-x-4">
                        <span class="text-gray-400 text-sm select-none">800 left</span>
                        <button
                            type="submit"
                            class="bg-black text-white text-sm font-semibold rounded-md px-4 py-2 hover:bg-gray-900 transition"
                        >
                            Post
                        </button>
                    </div>
                </div>
            </form>

            <!-- Posts Section -->
            <section class="space-y-8">
                <article
                    v-for="(post, index) in posts"
                    :key="index"
                    class="flex space-x-4"
                >
                    <img
                        :src="post.avatar"
                        alt="User avatar"
                        class="w-10 h-10 rounded-md object-cover flex-shrink-0"
                        width="40"
                        height="40"
                    />
                    <div class="flex-1">
                        <header class="flex items-center space-x-2 mb-1">
                            <p class="font-bold text-sm leading-none">{{ post.username }}</p>
                            <p
                                :class="[
                              'text-xs font-semibold rounded px-2 py-0.5 select-none',
                              post.vote === 'Yes'
                                ? 'bg-blue-200 text-blue-700'
                                : 'bg-purple-200 text-purple-700'
                            ]"
                                        >
                            {{ post.vote }}
                          </p>
                            <time class="text-xs text-gray-400 select-none">
                                {{ post.date }}
                            </time>
                        </header>
                        <p class="text-base leading-relaxed">
                            {{ post.content }}
                        </p>
                        <button
                            v-if="post.learnMore"
                            class="mt-1 text-sm text-gray-600 font-semibold hover:underline focus:outline-none"
                        >
                            Learn more
                        </button>
                        <nav
                            aria-label="Post actions"
                            class="flex space-x-6 mt-2 text-gray-500 text-sm select-none"
                        >
                            <button class="flex items-center space-x-1 hover:text-gray-700">
                                <i class="far fa-comment"></i>
                                <span>{{ post.comments }}</span>
                            </button>
                            <button class="flex items-center space-x-1 hover:text-gray-700">
                                <i class="far fa-heart"></i>
                                <span>{{ post.likes }}</span>
                            </button>
                            <button class="hover:text-gray-700">
                                <i class="far fa-bookmark"></i>
                            </button>
                            <button class="hover:text-gray-700">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </nav>
                    </div>
                </article>
            </section>
        </main>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const posts = ref([
    {
        username: 'Ryan44',
        vote: 'No',
        date: 'Jun 19',
        content:
            'Does this market resolve to no if Iran Is government no longer exist?',
        avatar:
            'https://storage.googleapis.com/a1aa/image/38a6c08e-f794-48e3-1eaf-c3cf9c8c4fc2.jpg',
        comments: 1,
        likes: 2,
        learnMore: false
    },
    {
        username: 'Fuktwit',
        vote: 'No',
        date: 'Jun 19',
        content: 'too ez',
        avatar:
            'https://storage.googleapis.com/a1aa/image/38a6c08e-f794-48e3-1eaf-c3cf9c8c4fc2.jpg',
        comments: 0,
        likes: 0,
        learnMore: false
    },
    {
        username: 'Popinski',
        vote: 'No',
        date: 'Jun 18',
        content:
            'This Iran regime seems like it pretty chill, willing to work for peace after getting bombed',
        avatar:
            'https://storage.googleapis.com/a1aa/image/76a51678-9519-4c47-48b4-6b621b5ddcba.jpg',
        comments: 7,
        likes: 0,
        learnMore: false
    },
    {
        username: 'willibeemin',
        vote: 'Yes',
        date: 'Jun 18',
        content: 'nothing ever happens?',
        avatar:
            'https://storage.googleapis.com/a1aa/image/38a6c08e-f794-48e3-1eaf-c3cf9c8c4fc2.jpg',
        comments: 2,
        likes: 5,
        learnMore: false
    },
    {
        username: 'LibsOfCEcot',
        vote: 'No',
        date: 'Jun 17',
        content:
            "If USA, via its proxy Israel, causes the government of Iran to fall that could ignite the Kurdish insurgency in Rojhilatê Kurdistan seeing how 'Jin, Jiyan, Azadî' was what the regular people of iran were chanting, or maybe Balochi insurgents take a chance, or maybe the Qutbis in Afghanistan or Syria might just take this opportunity to massacre Shias en mass seeing how all the Genocidal maniacs of ...",
        avatar:
            'https://storage.googleapis.com/a1aa/image/38a6c08e-f794-48e3-1eaf-c3cf9c8c4fc2.jpg',
        comments: 0,
        likes: 0,
        learnMore: true
    }
])
</script>
