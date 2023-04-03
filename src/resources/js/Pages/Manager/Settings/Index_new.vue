<template>
	<AppLayout>
		<template #content>
			<Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>

			<main class="max-w-7xl mx-auto pb-10 lg:py-12 lg:px-8">
				<div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
				<aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
					<nav class="space-y-1">
					<template v-for="(item, index) in items">
						<a href="#" @click.prevent="selectItem(index)" :class="{ 'font-bold': selectedIndex === index }" class="group rounded-md px-3 py-2 flex items-center text-sm font-medium text-gray-900 hover:text-gray-900 hover:bg-gray-50">
						<!-- Heroicon name: outline/user-circle -->
						<component :is="item.icon" class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500" aria-hidden="true" />
						<span class="truncate">
							{{ item.name }}
						</span>
						</a>	
					</nav>
				</aside>
		
				<!-- Payment details -->
				<div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
					<component :is="selectedItem.componentName" :data="selectedItem.componentData" />					
					<section aria-labelledby="payment-details-heading">

					</section>
		
					<!-- Plan -->
					<section aria-labelledby="plan-heading">

					</section>
		
					<!-- Billing history -->
					<section aria-labelledby="billing-history-heading">
					<div class="bg-white pt-6 shadow sm:rounded-md sm:overflow-hidden">

					</div>
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
import {
    UserCircleIcon,
  } from '@heroicons/vue/outline'  

const navigation = [
	{
		name: 'Item 1',
		componentName: 'Item1Component',
		componentData: { message: 'Este es el componente 1.' },
		icon: 'UserCircleIcon'
	},
	{
		name: 'Item 2',
		componentName: 'Item2Component',
		componentData: { message: 'Este es el componente 2.' },
		icon: 'UserCircleIcon'
	},
	{
		name: 'Item 3',
		componentName: 'Item3Component',
		componentData: { message: 'Este es el componente 3.' },
		icon: 'UserCircleIcon'
	}
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
		UserCircleIcon	
	},
	setup() {
		navigation
	},

	data() {

		return {
			toastMessage: "",
			labelType: "info",
			selectedIndex: 0,
			items: [
			{
				name: 'Item 1',
				componentName: 'Item1Component',
				componentData: { message: 'Este es el componente 1.'},
				icon: 'UserCircleIcon'
			},
			{
				name: 'Item 2',
				componentName: 'Item2Component',
				componentData: { message: 'Este es el componente 2.'},
				icon: 'UserCircleIcon'
			},
			{
				name: 'Item 3',
				componentName: 'Item3Component',
				componentData: { message: 'Este es el componente 3.'},
				icon: 'UserCircleIcon'
			}
			]			
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