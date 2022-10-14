
<template>
	<AppLayout>

		<template #content>

			<div class="focus-within:content flex-grow flex flex-col">
				<div class="mx-auto my-10 text-4xl font-bold
							xl: w-11/12
							lg: w-11/12
							flex justify-between">

					<div class="flex items-center">
						<h1>Centro de Mensajes</h1>
					</div>

					<div class="flex text-sm" v-if="selectedName">
						<Icons name="user" class="h-6 w-6 text-opacity-50" /> - {{this.selectedName}}
					</div>
				</div>

				<div class="lg:flex lg:items-center lg:justify-between">

				</div>
				<!--   TABLA -->
				<div class="bg-white overflow-hidden shadow-lg sm:rounded-lg mt-5">
					<div class="flex flex-row justify-between bg-white ">
						<!-- chat list -->
						<div class="flex flex-col w-2/5 border-r overflow-y-auto max-h-[70vh]">
							<!-- search compt -->
							<div class="border-b-2 py-4 px-2 bg-blue-100 text-center">
								<input class="shadow-sm text-sm border-gray-300 rounded-md" type="text" id="search"
									placeholder="Buscar...">
							</div>
							<!-- end search compt -->
							<!-- user list -->
							<div v-for="c in contacts" :key="c.id"
								class="flex flex-row py-4 px-4 justify-center items-center border-b hover:bg-gray-50 hover:cursor-pointer"
								:class="[(c.message.status != 'read' ? 'bg-gradient-to-r from-blue-500 to-blue-300 hover:bg-gradient-to-r hover:from-blue-400 hover:to-blue-200' : '')]"
								@click="getMessages(c)">
								<div class="w-1/6 mr-4">
									<div
										class="p-2 bg-yellow-500 rounded-full text-white font-semibold flex items-center justify-center">
										{{c.name.substr(0,2).toUpperCase()}}</div>
								</div>
								<div class="w-full">
									<div class="text-sm font-semibold text-white"
										:class="[(c.message.status != 'read' ? 'text-white' : 'text-black')]">{{ c.name
										}}<br>
										<span class="text-sm font-normal"
											:class="[(c.message.status != 'read' ? 'text-white' : 'text-gray-700')]">{{
											c.wa_id }}</span>
									</div>
								</div>
							</div>
							<!-- end user list -->
						</div>
						<!-- end chat list -->

						<!-- message -->
						<div class="w-full px-5 flex flex-col justify-between overflow-y-auto max-h-[70vh]"
							ref="container" id="message-box">

							<div class="flex flex-col mt-5">
								<div v-if="loading" class="mx-auto">
									<Icons name="loading" class="h-12 w-12 text-opacity-50" />
								</div>
								<div v-else v-for="m in messages" :key="m.id">

									<div class="flex mb-2" :class="m.type == 'in' ? 'justify-start' : 'justify-end' ">
										<div class="text-white py-3 px-4 max-w-md" :class="m.type == 'in' ? 'ml-2 rounded-br-3xl rounded-tr-3xl rounded-tl-xl bg-gray-400' 
										: 'mr-2 rounded-bl-3xl rounded-tl-3xl rounded-tr-xl bg-blue-400'">
											{{m.body}}
										</div>
									</div>
									<div class="flex text-sm mb-4 text-gray-600"
										:class="m.type == 'in' ? 'justify-start ml-2' : 'justify-end mr-2'">
										{{this.format(m.created_at)}}
									</div>
								</div>
							</div>


							<div class="py-5 border-t mt-20">
								<input class="send-msj w-full bg-gray-100 border-transparent py-3 px-3 rounded-xl"
									type="text" placeholder="type your message here..." />
							</div>
						</div>
						<!-- end message -->

						<!-- <div class="w-2/5 border-l-2 px-5">
						<div class="flex flex-col">
							<div class="font-semibold text-xl py-4">Mern Stack Group</div>
							<img
								src="https://source.unsplash.com/L2cxSuKWbpo/600x600"
								class="object-cover rounded-xl h-64"
								alt=""
							/>
							<div class="font-semibold py-4">Created 22 Sep 2021</div>
							<div class="font-light">
								Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt,
								perspiciatis!
							</div>
						</div>
					</div> -->
					</div>
				</div>
			</div>
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
import moment from 'moment';
import Icons from '@/Layouts/Components/Icons.vue';


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

export default {
	// props: {
	// 	contacts: Object
	// },

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
		CheckCircleIcon, ChevronRightIcon, MailIcon,
		moment,
		Icons
	},
	filters: {
		moment: function (date) {
			return moment(date).format('MMMM Do YYYY, h:mm:ss a');
		}
	},
	setup() {
		return {
			user,
			navigation,
			breadcrumbs,
			userNavigation,
			attachments,
			eventTypes,
			//   timeline,
			//   comments,
			//   applications
		}
	},
	data() {
		return {
			messages: "",
			selectedContact: "",
			intervalId: "",
			loading: false,
			selectedName: "",
			contacts: ""
		}
	},
	created() {
		this.getContacts()
	},
	methods: {

		format(date) {
			return moment(date).format('DD-MM-YYYY h:mm');
		},

		async getMessages(c) {

			this.loading = true
			this.selectedName = c.name

			let wa_id = c.wa_id
			const get = `${route('messages.list')}?wa_id=${wa_id}`

			clearInterval(this.intervalId)

			this.intervalId = setInterval(function () {
				axios.get(get)
					.then(response => {
						this.loading = false
						this.messages = response.data
						this.$nextTick(() => {
							var element = document.getElementById('message-box');
							element.scrollTop = element.scrollHeight

						})
					})

			}.bind(this), 3000)

		},

		async getContacts() {

			const get = `${route('contacts.list')}`

			setInterval(function () {
				axios.get(get)
					.then(response => {
						console.log(response.data.data)
						this.contacts = response.data.data
					})
			}.bind(this), 3000)

		},

		async sendTest() {

			const get = `${route('whatsapp.sendtest')}`

			const response = await fetch(get, { method: 'GET' })
			const message = await response.json()

			console.log(message)

		}
	}
}
</script>