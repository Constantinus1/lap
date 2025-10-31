<script setup lang="ts">
import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController';
import { edit } from '@/routes/profile';
import { send } from '@/routes/verification';
import { Form, Head, Link, usePage } from '@inertiajs/vue3';

import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    addresses: any[];
}

const props = defineProps<Props>();

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: edit().url,
    },
];

const page = usePage();
const user = page.props.auth.user;

import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const showAddressForm = ref(false);
const editingAddress = ref(null);
const addressForm = ref({});

const resetForm = () => ({
    address: '',
    city: '',
    state: '',
    country: '',
    pin_code: '',
});

const openAddressForm = (address = null) => {
    editingAddress.value = address;
    addressForm.value = address ? { ...address } : resetForm();
    showAddressForm.value = true;
};

const closeAddressForm = () => {
    showAddressForm.value = false;
    editingAddress.value = null;
    addressForm.value = resetForm();
};

const saveAddress = () => {
    const method = editingAddress.value ? 'put' : 'post';
    const url = editingAddress.value
        ? `/addresses/${editingAddress.value.id}`
        : '/addresses';

    router[method](url, addressForm.value, {
        onSuccess: closeAddressForm,
    });
};

const deleteAddress = (id) => {
    if (confirm('Are you sure you want to delete this address?')) {
        router.delete(`/addresses/${id}`);
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbItems">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall
                    title="Profile information"
                    description="Update your name and email address"
                />

                <Form
                    v-bind="ProfileController.update.form()"
                    class="space-y-6"
                    v-slot="{ errors, processing, recentlySuccessful }"
                >
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            class="mt-1 block w-full"
                            name="name"
                            :default-value="user.name"
                            required
                            autocomplete="name"
                            placeholder="Full name"
                        />
                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            name="email"
                            :default-value="user.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError class="mt-2" :message="errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="send()"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div
                            v-if="status === 'verification-link-sent'"
                            class="mt-2 text-sm font-medium text-green-600"
                        >
                            A new verification link has been sent to your email
                            address.
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button
                            :disabled="processing"
                            data-test="update-profile-button"
                            >Save</Button
                        >

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p
                                v-show="recentlySuccessful"
                                class="text-sm text-neutral-600"
                            >
                                Saved.
                            </p>
                        </Transition>
                    </div>
                </Form>
            </div>

            <div class="flex flex-col space-y-6 mt-8">
                <div class="flex items-center justify-between">
                    <div>
                        <HeadingSmall
                            title="Addresses"
                            description="Manage your shipping addresses"
                        />
                    </div>
                    <Button @click="openAddressForm()" size="sm">
                        Add Address
                    </Button>
                </div>

                <div v-if="props.addresses.length === 0" class="text-sm text-muted-foreground">
                    No addresses added yet.
                </div>

                <div v-else class="space-y-4">
                    <div
                        v-for="address in props.addresses"
                        :key="address.id"
                        class="rounded-lg border p-4 flex items-start justify-between"
                    >
                        <div>
                            <p class="font-medium">{{ address.address }}</p>
                            <p class="text-sm text-muted-foreground">
                                {{ address.city }}, {{ address.state }}
                            </p>
                            <p class="text-sm text-muted-foreground">
                                {{ address.country }} - {{ address.pin_code }}
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <Button @click="openAddressForm(address)" variant="outline" size="sm">
                                Edit
                            </Button>
                            <Button @click="deleteAddress(address.id)" variant="destructive" size="sm">
                                Delete
                            </Button>
                        </div>
                    </div>
                </div>

                <!-- Address Form Modal -->
                <div
                    v-if="showAddressForm"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                    @click.self="closeAddressForm"
                >
                    <div class="bg-background rounded-lg p-6 w-full max-w-md space-y-4">
                        <h3 class="text-lg font-semibold">
                            {{ editingAddress ? 'Edit Address' : 'Add New Address' }}
                        </h3>

                        <div class="space-y-4">
                            <div class="grid gap-2">
                                <Label for="address">Street Address</Label>
                                <Input
                                    id="address"
                                    v-model="addressForm.address"
                                    placeholder="123 Main Street"
                                />
                            </div>

                            <div class="grid gap-2">
                                <Label for="city">City</Label>
                                <Input
                                    id="city"
                                    v-model="addressForm.city"
                                    placeholder="Vienna"
                                />
                            </div>

                            <div class="grid gap-2">
                                <Label for="state">State</Label>
                                <Input
                                    id="state"
                                    v-model="addressForm.state"
                                    placeholder="Vienna"
                                />
                            </div>

                            <div class="grid gap-2">
                                <Label for="country">Country</Label>
                                <Input
                                    id="country"
                                    v-model="addressForm.country"
                                    placeholder="Austria"
                                />
                            </div>

                            <div class="grid gap-2">
                                <Label for="pin_code">PIN Code</Label>
                                <Input
                                    id="pin_code"
                                    v-model="addressForm.pin_code"
                                    placeholder="1010"
                                />
                            </div>
                        </div>

                        <div class="flex gap-2 justify-end">
                            <Button @click="closeAddressForm" variant="outline">
                                Cancel
                            </Button>
                            <Button @click="saveAddress">
                                {{ editingAddress ? 'Update' : 'Add' }} Address
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>
