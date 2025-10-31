<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Head, router } from '@inertiajs/vue3';
import { CreditCard, FileText } from 'lucide-vue-next';
import { ref, computed } from 'vue';

const props = defineProps({
    cartItems: Array,
    total: Number,
    addresses: Array,
    isGuest: Boolean,
});

const breadcrumbs = [{ title: 'Checkout', href: '/checkout' }];

const selectedAddress = ref(props.addresses[0]?.id || null);
const selectedPaymentMethod = ref('invoice');
const useNewAddress = ref(false);
const guestName = ref('');
const guestEmail = ref('');
const addressFields = ref({
    shipping_address: '',
    shipping_city: '',
    shipping_state: '',
    shipping_country: '',
    shipping_pin_code: '',
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    }).format(price);
};

const placeOrder = () => {
    const baseData = { payment_method: selectedPaymentMethod.value };

    if (props.isGuest) {
        router.post('/checkout/place-order', {
            ...baseData,
            customer_name: guestName.value,
            customer_email: guestEmail.value,
            ...addressFields.value,
        });
    } else if (useNewAddress.value) {
        router.post('/checkout/place-order', {
            ...baseData,
            new_address: true,
            ...addressFields.value,
        });
    } else {
        router.post('/checkout/place-order', {
            ...baseData,
            address_id: selectedAddress.value,
        });
    }
};
</script>

<template>
    <Head title="Checkout" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl font-bold">Checkout</h1>
                <p class="text-muted-foreground">Complete your order</p>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Customer Information (Guest) -->
                    <Card v-if="isGuest">
                        <CardHeader>
                            <CardTitle>Customer Information</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="space-y-2">
                                <Label for="guest-name">Full Name</Label>
                                <Input
                                    id="guest-name"
                                    v-model="guestName"
                                    placeholder="John Doe"
                                    required
                                />
                            </div>
                            <div class="space-y-2">
                                <Label for="guest-email">Email</Label>
                                <Input
                                    id="guest-email"
                                    type="email"
                                    v-model="guestEmail"
                                    placeholder="john@example.com"
                                    required
                                />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Shipping Address -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Shipping Address</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div v-if="!isGuest" class="space-y-4">
                                <div
                                    v-for="address in addresses"
                                    :key="address.id"
                                    class="flex items-start space-x-3 rounded-lg border p-4 cursor-pointer hover:bg-accent transition-colors"
                                    :class="{ 'border-primary bg-accent': selectedAddress === address.id && !useNewAddress }"
                                    @click="useNewAddress = false; selectedAddress = address.id"
                                >
                                    <input
                                        type="radio"
                                        :value="address.id"
                                        :checked="selectedAddress === address.id && !useNewAddress"
                                        :id="`address-${address.id}`"
                                        class="mt-1"
                                    />
                                    <Label :for="`address-${address.id}`" class="flex-1 cursor-pointer">
                                        <div class="space-y-1">
                                            <p class="font-medium">{{ address.city }}, {{ address.state }}</p>
                                            <p class="text-sm text-muted-foreground">
                                                {{ address.country }} - {{ address.pin_code }}
                                            </p>
                                        </div>
                                    </Label>
                                </div>

                                <!-- Add new address option -->
                                <div
                                    class="flex items-start space-x-3 rounded-lg border p-4 cursor-pointer hover:bg-accent transition-colors"
                                    :class="{ 'border-primary bg-accent': useNewAddress }"
                                    @click="useNewAddress = true"
                                >
                                    <input
                                        type="radio"
                                        :checked="useNewAddress"
                                        id="address-new"
                                        class="mt-1"
                                    />
                                    <Label for="address-new" class="flex-1 cursor-pointer">
                                        <p class="font-medium">Add new address</p>
                                    </Label>
                                </div>

                                <!-- New address form -->
                                <div v-if="useNewAddress" class="space-y-4 mt-4 pl-7">
                                    <div class="space-y-2">
                                        <Label for="new-shipping-address">Street Address</Label>
                                        <Input
                                            id="new-shipping-address"
                                            v-model="shippingAddress"
                                            placeholder="123 Main Street"
                                            required
                                        />
                                    </div>
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="space-y-2">
                                            <Label for="new-shipping-city">City</Label>
                                            <Input
                                                id="new-shipping-city"
                                                v-model="shippingCity"
                                                placeholder="Berlin"
                                                required
                                            />
                                        </div>
                                        <div class="space-y-2">
                                            <Label for="new-shipping-state">State</Label>
                                            <Input
                                                id="new-shipping-state"
                                                v-model="shippingState"
                                                placeholder="Berlin"
                                                required
                                            />
                                        </div>
                                    </div>
                                    <div class="grid gap-4 sm:grid-cols-2">
                                        <div class="space-y-2">
                                            <Label for="new-shipping-country">Country</Label>
                                            <Input
                                                id="new-shipping-country"
                                                v-model="shippingCountry"
                                                placeholder="Germany"
                                                required
                                            />
                                        </div>
                                        <div class="space-y-2">
                                            <Label for="new-shipping-pin-code">PIN Code</Label>
                                            <Input
                                                id="new-shipping-pin-code"
                                                v-model="shippingPinCode"
                                                placeholder="10115"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="space-y-4">
                                <div class="space-y-2">
                                    <Label for="shipping-address">Street Address</Label>
                                    <Input
                                        id="shipping-address"
                                        v-model="shippingAddress"
                                        placeholder="123 Main Street"
                                        required
                                    />
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label for="shipping-city">City</Label>
                                        <Input
                                            id="shipping-city"
                                            v-model="shippingCity"
                                            placeholder="Berlin"
                                            required
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="shipping-state">State</Label>
                                        <Input
                                            id="shipping-state"
                                            v-model="shippingState"
                                            placeholder="Berlin"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label for="shipping-country">Country</Label>
                                        <Input
                                            id="shipping-country"
                                            v-model="shippingCountry"
                                            placeholder="Germany"
                                            required
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="shipping-pin-code">PIN Code</Label>
                                        <Input
                                            id="shipping-pin-code"
                                            v-model="shippingPinCode"
                                            placeholder="10115"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Payment Method -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Payment Method</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div
                                class="flex items-start space-x-3 rounded-lg border p-4 cursor-pointer hover:bg-accent transition-colors"
                                :class="{ 'border-primary bg-accent': selectedPaymentMethod === 'invoice' }"
                                @click="selectedPaymentMethod = 'invoice'"
                            >
                                <input
                                    type="radio"
                                    value="invoice"
                                    v-model="selectedPaymentMethod"
                                    id="payment-invoice"
                                    class="mt-1"
                                />
                                <Label for="payment-invoice" class="flex-1 cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <FileText class="h-5 w-5" />
                                        <div class="space-y-1">
                                            <p class="font-medium">Invoice</p>
                                            <p class="text-sm text-muted-foreground">
                                                Pay by invoice after receiving your order
                                            </p>
                                        </div>
                                    </div>
                                </Label>
                            </div>

                            <div
                                class="flex items-start space-x-3 rounded-lg border p-4 cursor-pointer hover:bg-accent transition-colors"
                                :class="{ 'border-primary bg-accent': selectedPaymentMethod === 'credit_card' }"
                                @click="selectedPaymentMethod = 'credit_card'"
                            >
                                <input
                                    type="radio"
                                    value="credit_card"
                                    v-model="selectedPaymentMethod"
                                    id="payment-credit-card"
                                    class="mt-1"
                                />
                                <Label for="payment-credit-card" class="flex-1 cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <CreditCard class="h-5 w-5" />
                                        <div class="space-y-1">
                                            <p class="font-medium">Credit Card</p>
                                            <p class="text-sm text-muted-foreground">
                                                Pay securely with your credit card
                                            </p>
                                        </div>
                                    </div>
                                </Label>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Order Items -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Order Items</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-3">
                                <div v-for="item in cartItems" :key="item.id" class="flex gap-3">
                                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg bg-muted">
                                        <img
                                            :src="item.product_image"
                                            :alt="item.product_name"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                    <div class="flex flex-1 items-center justify-between">
                                        <div>
                                            <p class="font-medium">{{ item.product_name }}</p>
                                            <p class="text-sm text-muted-foreground">Quantity: {{ item.quantity }}</p>
                                        </div>
                                        <p class="font-medium">{{ formatPrice(item.subtotal) }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <Card class="sticky top-4">
                        <CardHeader>
                            <CardTitle>Order Summary</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-muted-foreground">Subtotal</span>
                                <span>{{ formatPrice(total) }}</span>
                            </div>
                            <div class="flex items-center justify-between text-sm">
                                <span class="text-muted-foreground">Shipping</span>
                                <span>Free</span>
                            </div>
                            <div class="border-t pt-4">
                                <div class="flex items-center justify-between font-bold text-lg">
                                    <span>Total</span>
                                    <span>{{ formatPrice(total) }}</span>
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Button
                                class="w-full"
                                size="lg"
                                @click="placeOrder"
                                :disabled="!isGuest && !useNewAddress && !selectedAddress"
                            >
                                Place Order
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
