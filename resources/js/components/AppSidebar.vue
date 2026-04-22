<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Calendar, LayoutGrid, Users, Clock } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';
import { computed } from 'vue';

const page = usePage();
const isAdmin = computed(() => (page.props.auth.user as any)?.role === 'admin');

const adminNavItems: NavItem[] = [
    { title: 'Dashboard', href: dashboard(), icon: LayoutGrid },
    { title: 'Pacientes', href: '/pacientes', icon: Users },
    { title: 'Agenda', href: '/agenda', icon: Calendar },
    { title: 'Citas', href: 'todas-las-citas', icon: Clock },
];

const pacienteNavItems: NavItem[] = [
    { title: 'Dashboard', href: dashboard(), icon: LayoutGrid },
    { title: 'Reservar sesión', href: '/disponibilidad', icon: Calendar },
    { title: 'Mis citas', href: '/mis-citas', icon: Clock },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="isAdmin ? adminNavItems : pacienteNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>