<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Head, router } from '@inertiajs/vue3';
import { Trash2, ShoppingBag } from 'lucide-vue-next';

const props = defineProps({
    cartItems: {
        type: Array,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
});

const breadcrumbs = [
    {
        title: 'Shopping Cart',
        href: '/cart',
    },
];

const formatPrice = (price) => {
    return new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
    }).format(price);
};

const updateQuantity = (itemId, event) => {
    const quantity = parseInt(event.target.value);
    if (quantity > 0) {
        router.patch(`/cart/${itemId}`, {
            quantity: quantity,
        });
    }
};

const removeItem = (itemId) => {
    router.delete(`/cart/${itemId}`, {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Shopping Cart" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4 md:p-6">
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl font-bold">Shopping Cart</h1>
                <p class="text-muted-foreground">Review your items before checkout</p>
            </div>

            <div v-if="cartItems.length === 0" class="flex flex-col items-center justify-center gap-4 py-12">
                <ShoppingBag class="h-24 w-24 text-muted-foreground" />
                <h2 class="text-2xl font-semibold">Your cart is empty</h2>
                <p class="text-muted-foreground">Add some products to get started</p>
                <Button as="a" href="/shop">Go to Shop</Button>
            </div>

            <div v-else class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 space-y-4">
                    <Card v-for="item in cartItems" :key="item.id">
                        <CardContent class="p-4">
                            <div class="flex gap-4">
                                <a :href="`/products/${item.product_id}`" class="flex-shrink-0">
                                    <div class="h-24 w-24 overflow-hidden rounded-lg bg-muted">
                                        <img
                                            :src="item.product_image"
                                            :alt="item.product_name"
                                            class="h-full w-full object-cover"
                                        />
                                    </div>
                                </a>
                                <div class="flex flex-1 flex-col gap-3">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <a :href="`/products/${item.product_id}`">
                                                <h3 class="font-semibold hover:text-primary transition-colors">
                                                    {{ item.product_name }}
                                                </h3>
                                            </a>
                                            <p class="text-sm text-muted-foreground mt-1">
                                                {{ formatPrice(item.product_price) }} each
                                            </p>
                                        </div>
                                        <Button
                                            variant="ghost"
                                            size="icon"
                                            @click="removeItem(item.id)"
                                            class="text-destructive hover:text-destructive"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="flex items-center gap-2">
                                            <label class="text-sm font-medium">Quantity:</label>
                                            <input
                                                type="number"
                                                min="1"
                                                :value="item.quantity"
                                                @change="(e) => updateQuantity(item.id, e)"
                                                class="w-20 rounded-md border border-input bg-background px-3 py-2 text-sm"
                                            />
                                        </div>
                                        <div class="ml-auto">
                                            <p class="text-lg font-bold">{{ formatPrice(item.subtotal) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

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
                            <Button class="w-full" size="lg" as="a" href="/checkout">
                                Proceed to Checkout
                            </Button>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
