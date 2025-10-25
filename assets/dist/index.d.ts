import { App } from 'vue';
import { default as default_2 } from 'swiper';
import { ObjectDirective } from 'vue';
import { SwiperOptions } from 'swiper/types';

declare interface Currency {
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

export declare function mergeRecursive(obj1: Record<string, any>, obj2: Record<string, any>, ignoreValues?: (string | null | undefined)[]): Record<string, any>;

export declare function ShopGoPlugin(app: App): void;

export declare function useAdditionalPurchaseAttachmentEditApp(props?: Record<string, any>): Promise<App<Element>>;

export declare function useCurrency(currencyOptions?: CurrencyFormatOptions): {
    isSubCurrency: () => boolean;
    getCurrentCurrency: () => Currency;
    getMainCurrency: () => Currency;
    format: (num: number | string, currency?: Currency, options?: CurrencyFormatOptions) => string;
    formatMainCurrency: (num: number | string, options?: CurrencyFormatOptions) => string;
    exchange: (num: number, currency: any) => number;
};

export declare function useProductAttributeEditApp(props?: Record<string, any>): Promise<App<Element>>;

export declare function useProductCart(): void;

export declare function useProductDiscountsEditApp(props?: Record<string, any>): Promise<App<Element>>;

export declare function useProductFeatureEditApp(props?: Record<string, any>): Promise<App<Element>>;

export declare function useProductVariantsEditApp(props?: Record<string, any>): Promise<App<Element>>;

export declare function useSwiper(selector?: HTMLElement | string, options?: SwiperOptions): Promise<default_2 | default_2>;

export declare const vColorpicker: ObjectDirective<HTMLInputElement>;

export declare const vTomSelect: ObjectDirective;

export declare const vTooltip: ObjectDirective;

export { }


declare global {
    var Spectrum: SpectrumGlobal;
}
