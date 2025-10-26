import { Discount, OrderTotal, Product, ProductVariant, Shipping } from '~shopgo/types/entity';
import { PriceSet } from '~shopgo/types/price';

export interface CartData {
  items: CartItem[];
  totals: Record<string, OrderTotal>;
  coupons: Discount[];
  discounts: Discount[];
  location: any;
  params: Record<string, any>;
  shipping: Shipping | null;
}

export interface CartItem {
  mainVariant: ProductVariant;
  variant: ProductVariant;
  product: Product;
  priceSet: PriceSet;
  quantity: number;
  cover: string;
  link: string;
  key: string;
  uid: string;
  outOfStock: boolean;
  payload: Record<string, any>;
  options: {
    checked: boolean;
    [name: string]: any;
  };
  attachments: CartItem[];
  discounts: Discount[];
}
