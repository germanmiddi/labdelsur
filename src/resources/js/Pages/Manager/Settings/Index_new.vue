<template>
<!-- eslint-disable -->
	<AppLayout>
		<template #content>
			<Toast :toast="this.toastMessage" :type="this.labelType" @clear="clearMessage"></Toast>

			<main class="max-w-7xl mx-auto pb-10 lg:py-12 lg:px-8">
				<div class="lg:grid lg:grid-cols-12 lg:gap-x-5">
					<aside class="py-6 px-2 sm:px-6 lg:py-0 lg:px-0 lg:col-span-3">
						<nav class="space-y-1">
							<a v-for="(item,index) in subNavigation" :key="item.name" @click.prevent="selectItem(index)"
								:class="[selectedIndex === index ? 'bg-gray-50 text-indigo-600 hover:bg-white' : 'text-gray-900 hover:text-gray-900 hover:bg-gray-50', 'group rounded-md px-3 py-2 flex items-center text-sm font-medium cursor-pointer']">
								<component :is="item.icon" :class="[selectedIndex === index ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-1 mr-3 h-6 w-6']" aria-hidden="true" />
								<span class="truncate">{{ item.name }}</span>
							</a>
						</nav>
					</aside>
			
					<!-- Payment details -->
					<div class="space-y-6 sm:px-6 lg:px-0 lg:col-span-9">
						<component :is="selectedItem.componentName" :data="selectedItem.componentData" />
					</div>
				</div>
			</main>

		</template>
	</AppLayout>

</template>


<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import Toast from '@/Layouts/Components/Toast.vue'
import General from './General.vue';
import Turnos from './Turnos.vue';
import Whatsapp from './Whatsapp.vue';
import Mensajes from './Mensajes.vue';
import { CogIcon,
     	 CalendarIcon,
		 ChatAltIcon,
	     AdjustmentsIcon,
  		} from '@heroicons/vue/outline'  

const subNavigation = [
    { name: 'General', icon: CogIcon, componentName: 'General' },
    { name: 'Turnos', icon: CalendarIcon, componentName: 'Turnos' },
    { name: 'API Whatsapp', icon: AdjustmentsIcon, componentName: 'Whatsapp' },
    { name: 'Mensajes', icon: ChatAltIcon, componentName: 'Mensajes' },
]

export default {
	props: {
	},

	components: {
		AppLayout,
		Toast,
		General,
		Turnos,
		Whatsapp,
		Mensajes
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
        return this.subNavigation[this.selectedIndex];
      }
    }
}
</script>