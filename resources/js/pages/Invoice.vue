<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
});

const breadcrumbs = [
    {
        title: 'Invoice',
        href: `/orders/${props.order.id}/invoice`,
    },
];

const formatPrice = (price) => {
    return new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    }).format(price);
};
</script>

<template>
    <Head :title="`Invoice #${order.id}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl font-bold">Invoice #{{ order.id }}</h1>
                <p class="text-muted-foreground">Order placed on {{ order.order_date }}</p>
            </div>

            <Card>
                <CardContent class="p-8">
                    <!-- Invoice Header -->
                    <div class="mb-8 flex justify-between">
                        <div>
                            <h2 class="text-2xl font-bold">INVOICE</h2>
                            <p class="text-sm text-muted-foreground mt-1">Invoice #{{ order.id }}</p>
                            <p class="text-sm text-muted-foreground">Date: {{ order.order_date }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg">Your Shop</p>
                            <p class="text-sm text-muted-foreground">123 Shop Street</p>
                            <p class="text-sm text-muted-foreground">12345 City</p>
                            <p class="text-sm text-muted-foreground">Germany</p>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="mb-8 grid gap-8 md:grid-cols-2">
                        <div>
                            <h3 class="font-semibold mb-2">Bill To:</h3>
                            <p class="font-medium">{{ order.user_name }}</p>
                            <p class="text-sm text-muted-foreground">{{ order.user_email }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold mb-2">Ship To:</h3>
                            <p class="text-sm">{{ order.address.address }}</p>
                            <p class="text-sm">{{ order.address.city }}, {{ order.address.state }}</p>
                            <p class="text-sm">{{ order.address.country }}</p>
                            <p class="text-sm">{{ order.address.pin_code }}</p>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-8">
                        <h3 class="font-semibold mb-2">Payment Method:</h3>
                        <p class="text-sm capitalize">
                            {{ order.payment_method.replace('_', ' ') }}
                        </p>
                    </div>

                    <!-- Items Table -->
                    <div class="mb-8">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="py-3 text-left font-semibold">Product</th>
                                    <th class="py-3 text-center font-semibold">Quantity</th>
                                    <th class="py-3 text-right font-semibold">Price</th>
                                    <th class="py-3 text-right font-semibold">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, index) in order.items"
                                    :key="index"
                                    class="border-b"
                                >
                                    <td class="py-3">{{ item.product_name }}</td>
                                    <td class="py-3 text-center">{{ item.quantity }}</td>
                                    <td class="py-3 text-right">{{ formatPrice(item.price) }}</td>
                                    <td class="py-3 text-right">{{ formatPrice(item.subtotal) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Total -->
                    <div class="flex justify-end">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Subtotal:</span>
                                <span>{{ formatPrice(order.order_amount) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-muted-foreground">Shipping:</span>
                                <span>Free</span>
                            </div>
                            <div class="flex justify-between border-t pt-2 text-lg font-bold">
                                <span>Total:</span>
                                <span>{{ formatPrice(order.order_amount) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-12 border-t pt-6 text-center text-sm text-muted-foreground">
                        <p>Thank you for your order!</p>
                        <p class="mt-1">If you have any questions, please contact us at support@yourshop.com</p>
                    </div>
                </CardContent>
            </Card>

            <div class="flex gap-2">
                <Button as="a" href="/shop" variant="outline">
                    Continue Shopping
                </Button>
            </div>
        </div>
    </AppLayout>
</template>

