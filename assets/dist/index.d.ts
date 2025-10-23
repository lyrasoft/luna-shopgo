import { App } from 'vue';
import { ComponentOptionsBase } from 'vue';
import { ComponentProvideOptions } from 'vue';
import { ComponentPublicInstance } from 'vue';
import { ObjectDirective } from 'vue';

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

export declare function mergeRecursive(obj1: Record<string, any>, obj2: Record<string, any>, ignoreValues?: (string | null | undefined)[]): Record<string, any>;

export declare function ShopGoPlugin(app: App): void;

export declare function useAdditionalPurchaseAttachmentEdit(el: Element | string, props?: Record<string, any>): Promise<ComponentPublicInstance<    {}, {}, {}, {}, {}, {}, {}, {}, false, ComponentOptionsBase<any, any, any, any, any, any, any, any, any, {}, {}, string, {}, {}, {}, string, ComponentProvideOptions>, {}, {}, "", {}, any>>;

export declare function useCurrency(): {
    isSubCurrency: () => boolean;
    getCurrentCurrency: () => Currency;
    getMainCurrency: () => Currency;
    format: (num: number | string, currency?: Currency, addCode?: boolean) => string;
    formatMainCurrency: (num: number | string, addCode?: boolean) => string;
    exchange: (num: number, currency: any) => number;
};

export declare function useProductCart(): void;

export declare const vColorpicker: ObjectDirective;

export declare const vTomSelect: ObjectDirective;

export declare const vTooltip: ObjectDirective;

export { }
