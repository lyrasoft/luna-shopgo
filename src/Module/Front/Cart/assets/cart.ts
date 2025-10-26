
import { useCartApp } from '@lyrasoft/shopgo';
import { data, domready } from '@windwalker-io/unicorn-next';

await domready();

const app = await useCartApp(data('cart.props'));
app.mount('cart-app');


