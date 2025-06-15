<template>
    <!-- Toolbar -->
    <q-toolbar class="bg-white text-dark q-px-xl q-py-sm shadow-1" elevated>
        <!-- Logo -->
        <q-toolbar-title>
            <div class="flex items-center gap-x-3 cursor-pointer" @click="$inertia.get('/')">
                <q-img width="40px" height="40px" src="/images/logo.svg" alt="WeBet Logo" />
                <div class="text-lg text-grey-10 text-weight-medium" style="line-height: 1">
                    WeBet<br />
                    <span class="text-sm text-grey-5">(Bet On Any Events)</span>
                </div>
            </div>
        </q-toolbar-title>

        <!-- Auth / Guest -->
        <div v-if="$page.props.auth?.user" class="px-4 row items-center q-gutter-sm hidden-xs">
            <q-btn @click="$inertia.get(route('dashboard'))" unelevated color="primary" label="Dashboard" no-caps />
            <div class="column items-end q-mr-sm">
                <div class="text-weight-medium">{{ $page.props.auth.user.name }}</div>
                <div class="text-caption text-grey">{{ $page.props.auth.user.email }}</div>
            </div>
            <q-btn flat round dense @click="menuVisible = !menuVisible">
                <q-icon name="account_circle" />
            </q-btn>
            <q-menu v-model="menuVisible" anchor="bottom right" self="top right">
                <q-list>
                    <q-item clickable @click="$inertia.get(route('profile.edit'))">
                        <q-item-section>My Profile</q-item-section>
                    </q-item>
                    <q-item clickable @click="$inertia.get(route('profile.edit-password'))">
                        <q-item-section>Change Password</q-item-section>
                    </q-item>
                    <q-item clickable @click="$inertia.delete(route('login.destroy'))">
                        <q-item-section>Logout</q-item-section>
                    </q-item>
                </q-list>
            </q-menu>
        </div>

        <div v-else class="row items-center q-gutter-sm hidden-xs">
            <q-btn @click="$inertia.get(route('login'))" flat label="Login" no-caps class="text-primary" />
            <q-btn @click="$inertia.get(route('register.create'))" unelevated color="primary" label="Register" no-caps />
        </div>
    </q-toolbar>

    <!-- Hero Section -->
    <section class="q-pa-xl bg-primary text-white">
        <div class="row items-center justify-between q-gutter-xl">
            <div class="col-12 col-md-6">
                <div class="text-h3 text-weight-bold">Welcome to WeBet</div>
                <div class="text-subtitle1 q-mt-sm">Bet on any event, anytime. Transparent, secure, and exciting.</div>
                <q-btn class="q-mt-md" color="white" text-color="primary" label="Get Started" @click="goToRegister" />
            </div>
            <div class="col-12 col-md-5">
                <q-img src="https://cdn.quasar.dev/img/mountains.jpg" ratio="16/9" class="rounded-borders shadow-2" />
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="q-pa-xl bg-grey-1">
        <div class="text-center text-h5 text-bold q-mb-xl">How It Works</div>
        <div class="row q-col-gutter-lg text-center">
            <div class="col-12 col-sm-4">
                <q-icon name="account_circle" size="48px" color="primary" />
                <div class="text-subtitle1 text-bold q-mt-sm">Create an Account</div>
                <div class="text-body2 text-grey-7">Sign up for free in seconds.</div>
            </div>
            <div class="col-12 col-sm-4">
                <q-icon name="sports_esports" size="48px" color="primary" />
                <div class="text-subtitle1 text-bold q-mt-sm">Place Your Bet</div>
                <div class="text-body2 text-grey-7">Choose an event and amount to bet.</div>
            </div>
            <div class="col-12 col-sm-4">
                <q-icon name="emoji_events" size="48px" color="primary" />
                <div class="text-subtitle1 text-bold q-mt-sm">Win & Withdraw</div>
                <div class="text-body2 text-grey-7">Claim your winnings securely.</div>
            </div>
        </div>
    </section>

    <!-- Live Stats -->
    <section class="q-pa-xl bg-grey-1">
        <div class="text-center text-h5 text-bold q-mb-xl">Live Platform Stats</div>
        <div class="row q-col-gutter-lg text-center">
            <div class="col-12 col-sm-4">
                <div class="text-h4 text-primary text-bold">18,240+</div>
                <div class="text-caption text-grey">Bets Placed</div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="text-h4 text-primary text-bold">₹3.2 Cr+</div>
                <div class="text-caption text-grey">Winnings Paid</div>
            </div>
            <div class="col-12 col-sm-4">
                <div class="text-h4 text-primary text-bold">8,400+</div>
                <div class="text-caption text-grey">Active Users</div>
            </div>
        </div>
    </section>


    <!-- Features -->
    <section class="q-pa-xl">
        <div class="text-center text-h5 text-bold q-mb-xl">Why Choose WeBet?</div>
        <div class="row q-col-gutter-lg q-row-gutter-xl">
            <div
                v-for="feature in features"
                :key="feature.title"
                class="col-12 col-md-4 flex"
            >
                <q-card class="q-pa-md shadow-2 rounded-borders flex flex-col items-center text-center full-width full-height">
                    <q-card-section class="flex flex-col items-center justify-center q-gutter-sm">
                        <q-icon :name="feature.icon" color="primary" size="48px" />
                        <div class="text-subtitle1 text-weight-bold">{{ feature.title }}</div>
                        <div class="text-body2 text-grey-7">{{ feature.desc }}</div>
                    </q-card-section>
                </q-card>
            </div>
        </div>
    </section>



    <!-- Testimonials -->
    <section class="q-pa-xl bg-grey-2">
        <div class="text-center text-h5 text-bold q-mb-xl">What Our Users Say</div>
        <div class="row q-col-gutter-md">
            <div
                v-for="(t, i) in testimonials"
                :key="i"
                class="col-12 col-md-4 flex"
            >
                <q-card class="q-pa-md shadow-1 rounded-borders full-width full-height flex flex-col justify-between">
                    <q-card-section class="q-pa-none flex flex-col justify-between grow">
                        <div class="text-body1 italic">"{{ t.quote }}"</div>
                        <div class="text-subtitle2 q-mt-sm text-right">— {{ t.name }}</div>
                    </q-card-section>
                </q-card>
            </div>
        </div>
    </section>


    <!-- FAQs -->
    <section class="q-pa-xl">
        <div class="text-center text-h5 text-bold q-mb-xl">Frequently Asked Questions</div>

        <div class="q-mx-auto shadow-1 rounded-borders" style="max-width: 700px;">
            <q-expansion-item
                v-for="faq in faqs"
                :key="faq.q"
                expand-separator
                :label="faq.q"
                class="q-mb-xs q-py-sm q-px-md bg-grey-1"
                header-class="text-primary text-subtitle1"
                expand-icon="keyboard_arrow_down"
                switch-toggle-side
                animated
                content-inset-level="0"
            >
            <div class="text-body2 text-grey-8 q-px-sm">
                {{ faq.a }}
            </div>
            </q-expansion-item>
        </div>
    </section>



    <!-- Call to Action -->
    <section class="q-pa-xl bg-primary text-white text-center">
        <div class="text-h5 text-bold">Ready to place your first bet?</div>
        <div class="text-subtitle1 q-mt-sm">Join thousands of winners today.</div>
        <q-btn class="q-mt-md" color="white" text-color="primary" label="Join Now" @click="goToRegister" />
    </section>

    <!-- Footer -->
    <footer class="q-pa-md bg-grey-9 text-white text-center">
        <div class="text-subtitle2">© 2025 WeBet. All rights reserved.</div>
        <div class="q-mt-sm">
            <q-btn flat label="Privacy Policy" class="text-white" size="sm" />
            <q-btn flat label="Terms of Service" class="text-white" size="sm" />
            <q-btn flat label="Contact" class="text-white" size="sm" />
        </div>
    </footer>
</template>
<script setup>
import { ref } from 'vue'

const menuVisible = ref(false)

const goToRegister = () => {
    window.location.href = '/register'
}

const features = [
    {
        icon: 'lock',
        title: 'Secure & Transparent',
        desc: 'All bets are recorded and verifiable. Your funds are safe with us.',
    },
    {
        icon: 'schedule',
        title: 'Real-time Betting',
        desc: 'Place bets on live events with instant updates.',
    },
    {
        icon: 'emoji_events',
        title: 'Exciting Rewards',
        desc: 'Win big with high payout odds and bonus incentives.',
    },
]

const testimonials = [
    { name: 'Amit Patel', quote: 'WeBet has changed the way I engage with sports. Easy and fun!' },
    { name: 'Sneha R.', quote: 'I love the clean interface and quick payouts.' },
    { name: 'John D.', quote: 'Most transparent platform I’ve used. Highly recommended!' },
]

const faqs = [
    { q: 'Is betting legal on this platform?', a: 'Yes, WeBet complies with all local laws and guidelines.' },
    { q: 'How do I withdraw my winnings?', a: 'Withdrawals can be made via UPI, bank transfer, or wallets.' },
    { q: 'Can I bet on international events?', a: 'Yes! We cover global sports, entertainment, and more.' },
]
</script>
