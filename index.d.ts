export {}

declare global {
    interface Window {
        product?: Product
        statuses?: Status[]
        productCategories?: Category[]
        availableCategories?: Category[]
        flasher: flasher
        productImages: SpatieImage[]
    }

    interface Product {
        code: string
        cost_price: string
        created_at: string
        description: string
        id: number
        name: string
        quantity: number
        selling_price: string
        status_id: number
        updated_at: string
    }

    interface Status {
        code: string
        created_at: string
        id: number
        name: string
        updated_at: string
    }

    interface Category {
        code: string
        created_at: string
        descriptio: string
        id: number
        name: string
        status_id : number
        updated_at: string
    }

    interface SpatieImage {
        id: number
        name: string
        file_name: string
        uuid: string
        preview_url: string
        original_url: string
        order: number
        extension: string
        size: number
    }
}
