<template>
<!-- eslint-disable -->
	<AppLayout>
		<template #content>
			<Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>

			<main class="max-w-7xl mx-auto pb-10 lg:py-12 lg:px-8">
				<div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
					<aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
						<nav class="space-y-1">
							<a v-for="item in subNavigation" :key="item.name" :href="item.href" 
								:class="[item.current ? 'bg-gray-50 text-orange-600 hover:bg-white' : 'text-gray-900 hover:text-gray-900 hover:bg-gray-50', 'group rounded-md px-3 py-2 flex items-center text-sm font-medium']" 
								:aria-current="item.current ? 'page' : undefined">
								<component :is="item.icon" :class="[item.current ? 'text-orange-500' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-6 w-6']" aria-hidden="true" />
								<span class="truncate">{{ item.name }}</span>
							</a>
						</nav>
					</aside>
			
					<!-- Payment details -->
					<div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
						<section aria-labelledby="payment-details-heading">
						<!-- <form action="#" method="POST">
							<div class="shadow sm:rounded-md sm:overflow-hidden">
							<div class="bg-white py-6 px-4 sm:p-6">
								<div>
								<h2 id="payment-details-heading" class="text-lg leading-6 font-medium text-gray-900">Payment details</h2>
								<p class="mt-1 text-sm text-gray-500">Update your billing information. Please note that updating your location could affect your tax rates.</p>
								</div>
			
								<div class="mt-6 grid grid-cols-4 gap-6">
								<div class="col-span-4 sm:col-span-2">
									<label for="first-name" class="block text-sm font-medium text-gray-700">First name</label>
									<input type="text" name="first-name" id="first-name" autocomplete="cc-given-name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm" />
								</div>
			
								<div class="col-span-4 sm:col-span-2">
									<label for="last-name" class="block text-sm font-medium text-gray-700">Last name</label>
									<input type="text" name="last-name" id="last-name" autocomplete="cc-family-name" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm" />
								</div>
			
								<div class="col-span-4 sm:col-span-2">
									<label for="email-address" class="block text-sm font-medium text-gray-700">Email address</label>
									<input type="text" name="email-address" id="email-address" autocomplete="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm" />
								</div>
			
								<div class="col-span-4 sm:col-span-1">
									<label for="expiration-date" class="block text-sm font-medium text-gray-700">Expration date</label>
									<input type="text" name="expiration-date" id="expiration-date" autocomplete="cc-exp" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm" placeholder="MM / YY" />
								</div>
			
								<div class="col-span-4 sm:col-span-1">
									<label for="security-code" class="flex items-center text-sm font-medium text-gray-700">
									<span>Security code</span>
									<QuestionMarkCircleIcon class="ml-1 flex-shrink-0 h-5 w-5 text-gray-300" aria-hidden="true" />
									</label>
									<input type="text" name="security-code" id="security-code" autocomplete="cc-csc" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm" />
								</div>
			
								<div class="col-span-4 sm:col-span-2">
									<label for="country" class="block text-sm font-medium text-gray-700">Country</label>
									<select id="country" name="country" autocomplete="country-name" class="mt-1 block w-full bg-white border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm">
									<option>United States</option>
									<option>Canada</option>
									<option>Mexico</option>
									</select>
								</div>
			
								<div class="col-span-4 sm:col-span-2">
									<label for="postal-code" class="block text-sm font-medium text-gray-700">ZIP / Postal code</label>
									<input type="text" name="postal-code" id="postal-code" autocomplete="postal-code" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-gray-900 focus:border-gray-900 sm:text-sm" />
								</div>
								</div>
							</div>
							<div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
								<button type="submit" class="bg-gray-800 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">Save</button>
							</div>
							</div>
						</form> -->
						</section>
			
						<!-- Plan -->
						<section aria-labelledby="plan-heading">
						<!-- <form action="#" method="POST">
							<div class="shadow sm:rounded-md sm:overflow-hidden">
							<div class="bg-white py-6 px-4 space-y-6 sm:p-6">
								<div>
								<h2 id="plan-heading" class="text-lg leading-6 font-medium text-gray-900">Plan</h2>
								</div>
			
								<RadioGroup v-model="selectedPlan">
								<RadioGroupLabel class="sr-only"> Pricing plans </RadioGroupLabel>
								<div class="relative bg-white rounded-md -space-y-px">
									<RadioGroupOption as="template" v-for="(plan, planIdx) in plans" :key="plan.name" :value="plan" v-slot="{ checked, active }">
									<div :class="[planIdx === 0 ? 'rounded-tl-md rounded-tr-md' : '', planIdx === plans.length - 1 ? 'rounded-bl-md rounded-br-md' : '', checked ? 'bg-orange-50 border-orange-200 z-10' : 'border-gray-200', 'relative border p-4 flex flex-col cursor-pointer md:pl-4 md:pr-6 md:grid md:grid-cols-3 focus:outline-none']">
										<div class="flex items-center text-sm">
										<span :class="[checked ? 'bg-orange-500 border-transparent' : 'bg-white border-gray-300', active ? 'ring-2 ring-offset-2 ring-gray-900' : '', 'h-4 w-4 rounded-full border flex items-center justify-center']" aria-hidden="true">
											<span class="rounded-full bg-white w-1.5 h-1.5" />
										</span>
										<RadioGroupLabel as="span" class="ml-3 font-medium text-gray-900">{{ plan.name }}</RadioGroupLabel>
										</div>
										<RadioGroupDescription class="ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-center">
										<span :class="[checked ? 'text-orange-900' : 'text-gray-900', 'font-medium']">${{ plan.priceMonthly }} / mo</span>
										{{ ' ' }}
										<span :class="checked ? 'text-orange-700' : 'text-gray-500'">(${{ plan.priceYearly }} / yr)</span>
										</RadioGroupDescription>
										<RadioGroupDescription :class="[checked ? 'text-orange-700' : 'text-gray-500', 'ml-6 pl-1 text-sm md:ml-0 md:pl-0 md:text-right']">{{ plan.limit }}</RadioGroupDescription>
									</div>
									</RadioGroupOption>
								</div>
								</RadioGroup>
			
								<SwitchGroup as="div" class="flex items-center">
								<Switch v-model="annualBillingEnabled" :class="[annualBillingEnabled ? 'bg-orange-500' : 'bg-gray-200', 'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-colors ease-in-out duration-200']">
									<span aria-hidden="true" :class="[annualBillingEnabled ? 'translate-x-5' : 'translate-x-0', 'inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200']" />
								</Switch>
								<SwitchLabel as="span" class="ml-3">
									<span class="text-sm font-medium text-gray-900">Annual billing </span>
									<span class="text-sm text-gray-500">(Save 10%)</span>
								</SwitchLabel>
								</SwitchGroup>
							</div>
							<div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
								<button type="submit" class="bg-gray-800 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900">Save</button>
							</div>
							</div>
						</form> -->
						</section>
			
						<!-- Billing history -->
						<section aria-labelledby="billing-history-heading">
						<!-- <div class="bg-white pt-6 shadow sm:rounded-md sm:overflow-hidden">
							<div class="px-4 sm:px-6">
							<h2 id="billing-history-heading" class="text-lg leading-6 font-medium text-gray-900">Billing history</h2>
							</div>
							<div class="mt-6 flex flex-col">
							<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
								<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
								<div class="overflow-hidden border-t border-gray-200">
									<table class="min-w-full divide-y divide-gray-200">
									<thead class="bg-gray-50">
										<tr>
										<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
										<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
										<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount
										
											`relative` is added here due to a weird bug in Safari that causes `sr-only` headings to introduce overflow on the body on mobile.</th>
										
										<th scope="col" class="relative px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
											<span class="sr-only">View receipt</span>
										</th>
										</tr>
									</thead>
									<tbody class="bg-white divide-y divide-gray-200">
										<tr v-for="payment in payments" :key="payment.id">
										<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
											<time :datetime="payment.datetime">{{ payment.date }}</time>
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
											{{ payment.description }}
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
											{{ payment.amount }}
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
											<a :href="payment.href" class="text-orange-600 hover:text-orange-900">View receipt</a>
										</td>
										</tr>
									</tbody>
									</table>
								</div>
								</div>
							</div>
							</div>
						</div> -->
						</section>
					</div>
				</div>
			</main>

		</template>
	</AppLayout>

</template>


<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Toast from '@/Layouts/Components/Toast.vue'
import Item1Component from './Item1Component.vue';
import Item2Component from './Item2Component.vue';
import Item3Component from './Item3Component.vue';
import { UserCircleIcon,
		 CogIcon,
		 KeyIcon,
		 BellIcon,
		 CreditCardIcon,
		 ViewGridAddIcon
  		} from '@heroicons/vue/outline'  

const subNavigation = [
    { name: 'Profile', href: '#', icon: UserCircleIcon, current: false },
    { name: 'Account', href: '#', icon: CogIcon, current: false },
    { name: 'Password', href: '#', icon: KeyIcon, current: false },
    { name: 'Notifications', href: '#', icon: BellIcon, current: false },
    { name: 'Plan & Billing', href: '#', icon: CreditCardIcon, current: true },
    { name: 'Integrations', href: '#', icon: ViewGridAddIcon, current: false },
]

export default {
	props: {
	},

	components: {
		AppLayout,
		Toast,
		Item1Component,
    	Item2Component,
    	Item3Component,	
		UserCircleIcon,
		CogIcon,
		KeyIcon,
		BellIcon,
		CreditCardIcon,
		ViewGridAddIcon
	},

	setup() {
		return{
			subNavigation
		}
	},

	data() {

		return {
			toastMessage: "",
			labelType:    "info",
			selectedIndex: 0

		
		}
	},
	watch: {

	},

	created() {

	},
    methods: {
      selectItem(index) {
        this.selectedIndex = index;
      }
    },
    computed: {
      selectedItem() {
        return this.items[this.selectedIndex];
      }
    }
}
</script>