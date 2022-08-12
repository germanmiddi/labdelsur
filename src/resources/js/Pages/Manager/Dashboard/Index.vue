
<template>
	<AppLayout>
		<template #content>
			<main class="">
			<!-- Page header -->
			<div class="max-w-3xl mx-auto px-4 sm:px-6 md:flex md:items-center md:justify-between md:space-x-5 lg:max-w-7xl lg:px-8">
				<div class="flex items-center space-x-5">
					<div>
						<h1 class="text-2xl font-bold text-gray-900">Mensajes</h1>
						<p class="text-sm font-medium text-gray-500">Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit, commodi.</p>
					</div>
				</div>
			</div>

			<div class="mt-8 max-w-3xl mx-auto grid grid-cols-1 gap-6 sm:px-6 lg:max-w-7xl lg:grid-flow-col-dense lg:grid-cols-3">
				<div class="space-y-6 lg:col-start-1 lg:col-span-2">
					<div class="bg-white shadow overflow-hidden sm:rounded-md">
						<ul role="list" class="divide-y divide-gray-200">
						<li v-for="application in applications" :key="application.applicant.email">
							<a :href="application.href" class="block hover:bg-gray-50">
							<div class="flex items-center px-4 py-4 sm:px-6">
								<div class="min-w-0 flex-1 flex items-center">
								<div class="flex-shrink-0">
									<img class="h-12 w-12 rounded-full" :src="application.applicant.imageUrl" alt="" />
								</div>
								<div class="min-w-0 flex-1 px-4 md:grid md:grid-cols-2 md:gap-4">
									<div>
									<p class="text-sm font-medium text-indigo-600 truncate">{{ application.applicant.name }}</p>
									<p class="mt-2 flex items-center text-sm text-gray-500">
										<MailIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
										<span class="truncate">{{ application.applicant.email }}</span>
									</p>
									</div>
									<div class="hidden md:block">
									<div>
										<p class="text-sm text-gray-900">
										Applied on
										{{ ' ' }}
										<time :datetime="application.date">{{ application.dateFull }}</time>
										</p>
										<p class="mt-2 flex items-center text-sm text-gray-500">
										<CheckCircleIcon class="flex-shrink-0 mr-1.5 h-5 w-5 text-green-400" aria-hidden="true" />
										{{ application.stage }}
										</p>
									</div>
									</div>
								</div>
								</div>
								<div>
								<ChevronRightIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
								</div>
							</div>
							</a>
						</li>
						</ul>
					</div>
				</div>

				<section aria-labelledby="timeline-title" class="lg:col-start-3 lg:col-span-1">
					<div class="bg-white px-4 py-5 shadow sm:rounded-lg sm:px-6">
						<h2 id="timeline-title" class="text-lg font-medium text-gray-900">Timeline</h2>

						<!-- Activity Feed -->
						<div class="mt-6 flow-root">
						<ul role="list" class="-mb-8">
							<li v-for="(item, itemIdx) in timeline" :key="item.id">
							<div class="relative pb-8">
								<span v-if="itemIdx !== timeline.length - 1" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true" />
								<div class="relative flex space-x-3">
								<div>
									<span :class="[item.type.bgColorClass, 'h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white']">
									<component :is="item.type.icon" class="w-5 h-5 text-white" aria-hidden="true" />
									</span>
								</div>
								<div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
									<div>
									<p class="text-sm text-gray-500">
										{{ item.content }} <a href="#" class="font-medium text-gray-900">{{ item.target }}</a>
									</p>
									</div>
									<div class="text-right text-sm whitespace-nowrap text-gray-500">
									<time :datetime="item.datetime">{{ item.date }}</time>
									</div>
								</div>
								</div>
							</div>
							</li>
						</ul>
						</div>
						<div class="mt-6 flex flex-col justify-stretch">
						  
						  <button @click="sendTest" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Send Test Message</button>
						</div>
					</div>
				</section>
			</div>
			</main>
		</template>
	</AppLayout>
</template>


<script>
import {
  Menu,
  MenuButton,
  MenuItem,
  MenuItems,
  Popover,
  PopoverButton,
  PopoverOverlay,
  PopoverPanel,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import {
  ArrowNarrowLeftIcon,
  CheckIcon,
  HomeIcon,
  PaperClipIcon,
  QuestionMarkCircleIcon,
  SearchIcon,
  ThumbUpIcon,
  UserIcon,
} from '@heroicons/vue/solid'

import { CheckCircleIcon, ChevronRightIcon, MailIcon } from '@heroicons/vue/solid'


import { BellIcon, MenuIcon, XIcon } from '@heroicons/vue/outline'
import AppLayout from '@/Layouts/AppLayout.vue';

const applications = [
  {
	applicant: {
	  name: 'Ricardo Cooper',
	  email: 'ricardo.cooper@example.com',
	  imageUrl:
		'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
	},
	date: '2020-01-07',
	dateFull: 'January 7, 2020',
	stage: 'Completed phone screening',
	href: '#',
  },
  {
	applicant: {
	  name: 'Kristen Ramos',
	  email: 'kristen.ramos@example.com',
	  imageUrl:
		'https://images.unsplash.com/photo-1550525811-e5869dd03032?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
	},
	date: '2020-01-07',
	dateFull: 'January 7, 2020',
	stage: 'Completed phone screening',
	href: '#',
  },
  {
	applicant: {
	  name: 'Ted Fox',
	  email: 'ted.fox@example.com',
	  imageUrl:
		'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
	},
	date: '2020-01-07',
	dateFull: 'January 7, 2020',
	stage: 'Completed phone screening',
	href: '#',
  },
]
const user = {
  name: 'Whitney Francis',
  email: 'whitney@example.com',
  imageUrl:
	'https://images.unsplash.com/photo-1517365830460-955ce3ccd263?ixlib=rb-=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=256&h=256&q=80',
}
const navigation = [
  { name: 'Dashboard', href: '#' },
  { name: 'Jobs', href: '#' },
  { name: 'Applicants', href: '#' },
  { name: 'Company', href: '#' },
]
const breadcrumbs = [
  { name: 'Jobs', href: '#', current: false },
  { name: 'Front End Developer', href: '#', current: false },
  { name: 'Applicants', href: '#', current: true },
]
const userNavigation = [
  { name: 'Your Profile', href: '#' },
  { name: 'Settings', href: '#' },
  { name: 'Sign out', href: '#' },
]
const attachments = [
  { name: 'resume_front_end_developer.pdf', href: '#' },
  { name: 'coverletter_front_end_developer.pdf', href: '#' },
]
const eventTypes = {
  applied: { icon: UserIcon, bgColorClass: 'bg-gray-400' },
  advanced: { icon: ThumbUpIcon, bgColorClass: 'bg-blue-500' },
  completed: { icon: CheckIcon, bgColorClass: 'bg-green-500' },
}
const timeline = [
  {
	id: 1,
	type: eventTypes.applied,
	content: 'Applied to',
	target: 'Front End Developer',
	date: 'Sep 20',
	datetime: '2020-09-20',
  },
  {
	id: 2,
	type: eventTypes.advanced,
	content: 'Advanced to phone screening by',
	target: 'Bethany Blake',
	date: 'Sep 22',
	datetime: '2020-09-22',
  },
  {
	id: 3,
	type: eventTypes.completed,
	content: 'Completed phone screening with',
	target: 'Martha Gardner',
	date: 'Sep 28',
	datetime: '2020-09-28',
  },
  {
	id: 4,
	type: eventTypes.advanced,
	content: 'Advanced to interview by',
	target: 'Bethany Blake',
	date: 'Sep 30',
	datetime: '2020-09-30',
  },
  {
	id: 5,
	type: eventTypes.completed,
	content: 'Completed interview with',
	target: 'Katherine Snyder',
	date: 'Oct 4',
	datetime: '2020-10-04',
  },
]
const comments = [
  {
	id: 1,
	name: 'Leslie Alexander',
	date: '4d ago',
	imageId: '1494790108377-be9c29b29330',
	body: 'Ducimus quas delectus ad maxime totam doloribus reiciendis ex. Tempore dolorem maiores. Similique voluptatibus tempore non ut.',
  },
  {
	id: 2,
	name: 'Michael Foster',
	date: '4d ago',
	imageId: '1519244703995-f4e0f30006d5',
	body: 'Et ut autem. Voluptatem eum dolores sint necessitatibus quos. Quis eum qui dolorem accusantium voluptas voluptatem ipsum. Quo facere iusto quia accusamus veniam id explicabo et aut.',
  },
  {
	id: 3,
	name: 'Dries Vincent',
	date: '4d ago',
	imageId: '1506794778202-cad84cf45f1d',
	body: 'Expedita consequatur sit ea voluptas quo ipsam recusandae. Ab sint et voluptatem repudiandae voluptatem et eveniet. Nihil quas consequatur autem. Perferendis rerum et.',
  },
]

export default {
  components: {
	Menu,
	MenuButton,
	MenuItem,
	MenuItems,
	Popover,
	PopoverButton,
	PopoverOverlay,
	PopoverPanel,
	TransitionChild,
	TransitionRoot,
	ArrowNarrowLeftIcon,
	BellIcon,
	HomeIcon,
	MenuIcon,
	PaperClipIcon,
	QuestionMarkCircleIcon,
	SearchIcon,
	XIcon,
	AppLayout,
	CheckCircleIcon, ChevronRightIcon, MailIcon    
	

  },
  setup() {
	return {
	  user,
	  navigation,
	  breadcrumbs,
	  userNavigation,
	  attachments,
	  eventTypes,
	  timeline,
	  comments,
	  applications
	}
  },
  data(){

  },
  methods:{
	async sendTest(){

		const get = `${route('whatsapp.sendtest')}` 

		const response = await fetch(get, {method:'GET'})
		const message = await response.json() 
		
		console.log(message)

	}
  }
}
</script>