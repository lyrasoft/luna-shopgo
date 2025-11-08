import { App } from 'vue';
import { default as default_2 } from 'swiper';
import { ObjectDirective } from 'vue';
import { SwiperOptions } from 'swiper/types';

export declare interface Address {
    id?: number | null;
    userId: number;
    locationId: number;
    firstname: string;
    lastname: string;
    name: string;
    email: string;
    phone: string;
    mobile: string;
    company: string;
    country: string;
    state: string;
    city: string;
    postcode: string;
    address1: string;
    address2: string;
    vat: string;
    formatted: string;
    details: any;
    enabled: boolean;
    created: string | null;
    modified: string | null;
    [prop: string]: any;
}

export declare type AddressFormData = Address & {
    addressId?: string | number;
    locationPath: (number | string)[];
    formatted: string;
};

export declare interface CartData {
    items: CartItem[];
    totals: Record<string, OrderTotal>;
    coupons: Discount[];
    discounts: Discount[];
    location: any;
    params: Record<string, any>;
    shipping: Shipping | null;
}

export declare interface CartItem {
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

export declare interface Currency {
    id: number;
    title: string;
    code: string;
    codeNum: number;
    sign: string;
    signPosition: string;
    decimalPlace: any;
    decimalPoint: string;
    numSeparator: string;
    exchangeRate: number;
    space: boolean;
    state: boolean;
    created: string | null;
    modified: string | null;
    createdBy: number;
    modifiedBy: number;
    params: any;
    [prop: string]: any;
}

export declare interface CurrencyFormatOptions {
    code?: boolean;
    sign?: boolean;
    signPosition?: 'start' | 'end';
}

export declare interface Discount {
    id: number;
    productId: number;
    type: string;
    subtype: string;
    title: string;
    price: number;
    publishUp: string | null;
    publishDown: string | null;
    description: string;
    code: string;
    notice: string;
    ordering: number;
    state: boolean;
    hide: boolean;
    minPrice: number | null;
    quantity: number | null;
    timesPerUser: number | null;
    firstBuy: number | null;
    afterRegistered: number | null;
    canRollback: boolean;
    combine: string;
    combineTargets: any;
    users: any;
    categories: any;
    products: any;
    tags: any;
    payments: any;
    shippings: any;
    applyProducts: any;
    minProductQuantity: number | null;
    minCartItems: number | null;
    minCartPrice: number | null;
    freeShipping: boolean;
    method: string;
    accumulate: boolean;
    applyTo: string;
    created: string | null;
    modified: string | null;
    createdBy: number;
    modifiedBy: number;
    params: any;
    [prop: string]: any;
}

export declare interface ListOption {
    uid: string;
    text: string;
    value: string;
    is_default?: boolean;
    [name: string]: any;
}

export declare function mergeRecursive(obj1: Record<string, any>, obj2: Record<string, any>, ignoreValues?: (string | null | undefined)[]): Record<string, any>;

export declare interface OrderTotal {
    id: number;
    orderId: number;
    discountId: number;
    discountType: string;
    type: string;
    title: string;
    code: string;
    value: number;
    ordering: number;
    protect: boolean;
    params: any;
    [prop: string]: any;
}

export declare interface Payment {
    id: number;
    locationCategoryId: number;
    locationId: number;
    orderStateId: number;
    classname: string;
    type: string;
    title: string;
    subtitle: string;
    alias: string;
    description: string;
    note: string;
    image: string;
    state: boolean;
    ordering: number;
    created: string | null;
    modified: string | null;
    createdBy: number;
    modifiedBy: number;
    params: any;
    [prop: string]: any;
}

export declare interface PriceObject {
    name: string;
    label: string;
    price: string;
    params: Record<string, any>;
}

export declare type PriceSet = {
    origin: PriceObject;
    base: PriceObject;
    final: PriceObject;
    total: PriceObject;
    grand_total: PriceObject;
    shipping_fee: PriceObject;
    [name: string]: PriceObject;
};

export declare interface Product {
    id: number;
    categoryId: number;
    primaryVariantId: number;
    model: string;
    title: string;
    alias: string;
    originPrice: number;
    safeStock: number;
    intro: string;
    description: string;
    meta: any;
    canAttach: boolean;
    variants: number;
    ordering: number;
    hide: boolean;
    state: boolean;
    searchIndex: string;
    shippings: any;
    publishUp: string | null;
    publishDown: string | null;
    created: string | null;
    modified: string | null;
    createdBy: number;
    modifiedBy: number;
    hits: number;
    params: any;
    [prop: string]: any;
}

export declare interface ProductAttribute {
    id: number;
    categoryId: number;
    type: string;
    title: string;
    key: string;
    display: boolean;
    ordering: number;
    state: boolean;
    options: ListOption[];
    created: string | null;
    modified: string | null;
    createdBy: number;
    modifiedBy: number;
    params: any;
    [prop: string]: any;
}

export declare interface ProductFeature {
    id: number;
    type: string;
    title: string;
    default: string;
    note: string;
    ordering: number;
    state: boolean;
    options: ListOption[];
    created: string | null;
    modified: string | null;
    createdBy: number;
    modifiedBy: number;
    params: any;
    [prop: string]: any;
}

export declare interface ProductVariant {
    id: number;
    productId: number;
    title: string;
    hash: string;
    primary: boolean;
    sku: string;
    upc: string;
    ean: string;
    jan: string;
    isbn: string;
    mpn: string;
    stockQuantity: number;
    subtract: boolean;
    price: number;
    dimension: any;
    stockBuyable: boolean;
    outOfStockText: string;
    cover: string;
    images: any;
    options: any;
    state: boolean;
    created: string | null;
    modified: string | null;
    createdBy: number;
    modifiedBy: number;
    params: any;
    [prop: string]: any;
}

export declare interface Shipping<Params = any> {
    id: number;
    locationCategoryId: number;
    locationId: number;
    classname: string;
    type: string;
    title: string;
    subtitle: string;
    alias: string;
    description: string;
    note: string;
    image: string;
    payments: any;
    allowTags: any;
    unallowTags: any;
    pricing: any;
    state: boolean;
    ordering: number;
    created: string | null;
    modified: string | null;
    createdBy: number;
    modifiedBy: number;
    params: Params;
    [prop: string]: any;
}

export declare function ShopGoPlugin(app: App): void;

export declare function useAdditionalPurchaseAttachmentEditApp(props?: Record<string, any>): Promise<App<Element>>;

export declare function useCartApp(props?: Record<string, any>): Promise<App<Element>>;

export declare function useCurrency(currencyOptions?: CurrencyFormatOptions): {
    isSubCurrency: () => boolean;
    getCurrentCurrency: () => Currency;
    getMainCurrency: () => Currency;
    format: (num: number | string, currency?: Currency, options?: CurrencyFormatOptions) => string;
    formatMainCurrency: (num: number | string, options?: CurrencyFormatOptions) => string;
    exchange: (num: number, currency: any) => number;
};

export declare function useCurrencySwitcher(): Promise<void>;

export declare function useProductAttributeEditApp(props?: Record<string, any>): Promise<App<Element>>;

export declare function useProductCartButtons(): Promise<void>;

export declare function useProductDiscountsEditApp(props?: Record<string, any>): Promise<App<Element>>;

export declare function useProductFeatureEditApp(props?: Record<string, any>): Promise<App<Element>>;

export declare function useProductVariantsEditApp(props?: Record<string, any>): Promise<App<Element>>;

export declare interface User {
    id: number | string;
    email: string;
    name: string;
    [name: string]: any;
}

export declare function useShopGoCatalog(): {
    $shopgo: {
        useProductCartButtons: typeof useProductCartButtons;
        useCurrencySwitcher: typeof useCurrencySwitcher;
    };
};

export declare function useSwiper(selector?: HTMLElement | string, options?: SwiperOptions): Promise<default_2 | default_2>;

export declare const vColorpicker: ObjectDirective<HTMLInputElement>;

export declare const vTomSelect: ObjectDirective;

export declare const vTooltip: ObjectDirective;

export { }


declare global {
    var Spectrum: SpectrumGlobal;
}
