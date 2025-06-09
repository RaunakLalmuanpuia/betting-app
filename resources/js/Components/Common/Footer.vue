<template>
    <div class="container q-py-xl q-px-lg">
        <div class="row">
            <div class="col-xs-6 col-sm-3 column q-gutter-md">
                <p class="f-title q-mb-md">Services</p>
                <a :href="route('service.firm-registration')" class="f-caption">Firm Registration</a>
                <a :href="route('service.activity-report')" class="f-caption">Yearly Report Submission</a>
                <a :href="route('service.partner-registration')" class="f-caption">Partnership Registration</a>
                <a :href="route('grievance.index')" class="f-caption">Grievances</a>
            </div>
            <div class="col-xs-6 col-sm-3 column q-gutter-md">
                <p class="f-title">About Us</p>
                <a :href="route('page.society')" class="f-caption">Firm & Society</a>
                <a :href="route('page.taxation')" class="f-caption">Taxation Department</a>
                <a :href="route('page.gom')" class="f-caption">Govt of Mizoram</a>
                <a :href="route('page.contact')" class="f-caption">Contact Us</a>
            </div>
            <div class="col-xs-6 col-sm-3 column q-gutter-md">
                <p class="f-title">Legal</p>
                <a :href="route('page.policy')" class="f-caption">Privacy Policy</a>
                <a :href="route('page.term')" class="f-caption">Term and Condition</a>
                <a :href="route('page.refund')" class="f-caption">Refund policy</a>
                <a :href="route('page.faq')" class="f-caption">Frequently Asked Question(FAQ)</a>
            </div>
            <div class="col-xs-6 col-sm-3 column q-gutter-md">
                <p class="f-title">Owned by</p>
                <div class="flex q-gutter-sm">
                    <q-img width="36px" fit="cover" src="/images/chakra.png"/>
                    <div class="column">
                        <p class="f-title q-ma-none">Taxation Department</p>
                        <p style="line-height: 0.2" class="f-caption q-ma-none">Govt of India</p>
                    </div>
                </div>
                <div class="flex q-gutter-sm">
                    <q-img width="36px" fit="cover" src="/images/msegs.png"/>
                    <div class="column">
                        <p class="f-title q-ma-none">Developed by</p>
                        <p style="line-height: 0.2" class="f-caption q-ma-none">Mizoram State e-Governance Society</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>

</script>
<style scoped>
.f-title{
    margin: 0;
    padding: 0;
    font-family: Poppins,serif;
    font-size: 18px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    letter-spacing: normal;
    text-align: left;
    color: #191c51;
}
.f-caption{
    cursor: pointer;
    margin-left: 0;
    margin-top: 12px;
    padding: 0;
    font-family: Roboto,serif;
    font-size: 14px;
    font-weight: normal;
    color: #191c51;
    opacity: 0.8;
    text-decoration: none;
}
</style>
