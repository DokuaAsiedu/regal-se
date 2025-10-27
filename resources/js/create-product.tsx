import { createRoot } from "react-dom/client"
import { PlaceholderText, RequiredComponent, Spinner } from "./components"
import { IoIosCloseCircleOutline } from "react-icons/io"
import { useForm, SubmitHandler } from "react-hook-form"
import * as z from "zod"
import { zodResolver } from "@hookform/resolvers/zod"
import { useEffect, useMemo, useRef, useState } from "react"
import { BsFillPlusCircleFill } from "react-icons/bs"

const product = window.product
const statuses = window.statuses || []
const productCategories = window.productCategories || []
const availableCategories = window.availableCategories || []
const images = window.productImages || []

const productSchema = z.object({
    name: z.string().nonempty(),
    code: z.string(),
    cost_price: z.coerce.number().nonnegative().or(z.literal("")),
    selling_price: z.coerce.number().nonnegative().or(z.literal("")),
    quantity: z.coerce.number().int().gte(1).or(z.literal("")),
    description: z.string(),
    status_id: z.literal(statuses.map((item) => item.id), "Please select a valid status").or(z.string("")),
    product_categories: z.array(z.number()).min(0),
    product_images: z.array(z.file().mime(["image/gif", "image/jpeg", "image/png", "image/webp", "image/svg+xml"])).min(0)
})

type ProductSchema = z.infer<typeof productSchema>

const initialProductState: ProductSchema = {
    name: product?.name || "Product One",
    code: product?.code || "",
    cost_price: product?.cost_price ? Number(product.cost_price) : "",
    selling_price: product?.selling_price ? Number(product.selling_price) : "",
    quantity: product?.quantity ? Number(product.quantity) : "",
    status_id: product?.status_id ? Number(product.status_id) : "",
    description: product?.description || "",
    product_categories: productCategories.map((item) => item.id),
    product_images: []
}

function CreateProduct() {
    const {
        register,
        handleSubmit,
        setValue,
        clearErrors,
        formState: { errors },
    } = useForm({
        resolver: zodResolver(productSchema),
        defaultValues: initialProductState,
    })
    const title = useMemo(() => product ? "Edit Product" : "Create New Product", [])
    const [selectedCategory, setSelectedCategory] = useState("")
    const [selectedCategories, setSelectedCategories] = useState<Category[]>(productCategories)
    const computedAvailableCategories = useMemo(() => getCategories(), [selectedCategories])
    const [loading, setLoading] = useState(false)
    const productImageRef = useRef<HTMLInputElement>(null)
    const [productImages, setProductImages] = useState<File[]>([])
    const [deletedImageIds, setDeletedImageIds] = useState<number[]>([])
    const computedExistingImages = useMemo(() => getExistingImages(), [deletedImageIds])

    function getCategories() {
        const categories = availableCategories.filter((item) => !selectedCategories.find((elem) => elem.id == item.id))

        return categories
    }

    function getExistingImages() {
        const existingImages = images.filter((item) => !deletedImageIds.find((elem) => elem == item.id))

        return existingImages
    }

    const addCategory = (id: string) => {
        const match = availableCategories.find((item) => item.id == id as unknown as number)
        if (match) {
            setSelectedCategories((prev) => [...prev, match])
        }
        setSelectedCategory("")
    }

    const removeCategory = (id: number) => {
        const match = selectedCategories.find((item) => item.id == id)
        if (match) {
            setSelectedCategories((prev) => prev.filter((item) => item.id != id))
        }
    }

    const triggerImageUpload = () => {
        productImageRef.current?.click()
    }

    const addProductImage = (e: React.ChangeEvent<HTMLInputElement>) => {
        const files = e.target.files
        if (files?.length) {
            setProductImages((prev) => [...prev, ...files])
        }
    }

    const removeProductImage = (index: number) => {
        setProductImages((prev) => prev.filter((_, pos) => pos != index))
    }

    const removeExistingImage = (id: number) => {
        setDeletedImageIds((prev) => [...prev, id])
    }

    const onSubmit: SubmitHandler<ProductSchema> = async (data) => {
        setLoading(true)
        const formData = new FormData()
        const arr = ["product_images", "product_categories"]
        Object.entries(data).forEach(([key, value]) => {
            if (value != undefined) {
                if (arr.includes(key)) {
                    if (Array.isArray(value)) {
                        value.forEach((item) => {
                            formData.append(`${key}[]`, item as any)
                        })
                    } else {
                        formData.append(`${key}[]`, value as any)
                    }
                } else {
                    formData.append(key, value as never)
                }
            }
        })
        let route
        let successMessage
        let errorMessage
        if (product) {
            route = "/api/product/update"
            successMessage = "Product successfully updated"
            errorMessage = "Error updating product"
            formData.append("id", product.id as never)
            deletedImageIds.forEach((item) => {
                formData.append("deleted_image_ids[]", item as never)
            })
        } else {
            route = "/api/product/store"
            successMessage = "Product successfully saved"
            errorMessage = "Error saving product"
        }
        try {
            const response = await fetch(route, {
                method: "POST",
                body: formData,
            })
            const res = await response.json()
            if (!response.ok) {
                throw new Error(res?.message || errorMessage)
            }
            if (res.redirect) {
                window.location = res.redirect
            }
            window.flasher.success(successMessage)
        } catch (err) {
            if (err instanceof Error) {
                window.flasher.error(err.message)
            } else {
                window.flasher(errorMessage)
            }
        } finally {
            setLoading(false)
        }
    }

    useEffect(() => {
        setValue("product_categories", selectedCategories.map((item) => item.id))
    }, [selectedCategories])

    useEffect(() => {
        setValue("product_images", productImages)
        clearErrors("product_images")
    }, [productImages])

    return (
        <div className="flex flex-col gap-5">
            <h1 className="text-2xl font-medium">{title}</h1>

            <form className="grid lg:grid-cols-2 gap-6" onSubmit={handleSubmit(onSubmit)}>
                <div className="flex flex-col gap-2">
                    <label htmlFor="name">Name <RequiredComponent /></label>
                    <input type="text" id="name" {...register("name")} />
                    {errors.name && <PlaceholderText text={errors.name.message} />}
                </div>

                <div className="flex flex-col gap-2">
                    <label htmlFor="code">Code</label>
                    <input type="text" id="code" {...register("code")} />
                    {errors.code && <PlaceholderText text={errors.code.message} />}
                </div>

                <div className="flex flex-col gap-2">
                    <label htmlFor="cost_price">Cost Price <RequiredComponent /></label>
                    <input type="number" step="0.01" id="cost_price" {...register("cost_price")} />
                    {errors.cost_price && <PlaceholderText text={errors.cost_price.message} />}
                </div>

                <div className="flex flex-col gap-2">
                    <label htmlFor="selling_price">Selling Price</label>
                    <input type="number" step="0.01" id="selling_price" {...register("selling_price")} />
                    {errors.selling_price && <PlaceholderText text={errors.selling_price.message} />}
                </div>

                <div className="flex flex-col gap-2">
                    <label htmlFor="quantity">Quantity <RequiredComponent /></label>
                    <input type="number" id="quantity" {...register("quantity")} />
                    {errors.quantity && <PlaceholderText text={errors.quantity.message} />}
                </div>

                <div className="flex flex-col gap-2">
                    <label htmlFor="description">Description</label>
                    <input type="text" id="description" {...register("description")} />
                    {errors.description && <PlaceholderText text={errors.description.message} />}
                </div>

                <div className="flex flex-col gap-2">
                    <label htmlFor="status">Status <RequiredComponent /></label>
                    <select id="status" {...register("status_id")}>
                        <option value="" disabled>Select an option...</option>
                        {statuses?.map((item, index) => (
                            <option key={`item-${index}`} value={item.id}>{item.name}</option>
                        ))}
                    </select>
                    {errors.status_id && <PlaceholderText text={errors.status_id.message} />}
                </div>

                <div className="flex flex-col gap-2">
                    <label htmlFor="available-categories">Available Categories</label>
                    <select id="available-categories" value={selectedCategory} onChange={(e) => addCategory(e.target.value)}>
                        <option value="" disabled>Select a category...</option>
                        {computedAvailableCategories.map((item, index) => (
                            <option key={`item-${index}`} value={item.id}>{item.name}</option>
                        ))}
                    </select>
                </div>

                <div className="lg:col-span-2 flex flex-col gap-6">
                    <h2>Categories</h2>
                    <div className="flex flex-wrap gap-4">
                        {!selectedCategories.length ?
                            <div className="px-6 py-2 rounded-full bg-gray-300">
                                <PlaceholderText text="No categories assigned" fontColor="black" />
                            </div> :
                            selectedCategories.map((item, index) => (
                                <span key={`item-${index}`} className="px-6 py-2 flex items-center gap-2 bg-cyan-200 rounded-full">
                                    <span>{item.name}</span>
                                    <IoIosCloseCircleOutline className="cursor-pointer text-red-500" onClick={() => removeCategory(item.id)} />
                                </span>
                            ))
                        }
                    </div>
                    {errors.product_categories && <PlaceholderText text={errors.product_categories.message} />}
                </div>

                <div className="lg:col-span-2 flex flex-col gap-2">
                    <h1>Upload product images</h1>
                    <div>
                        <PlaceholderText text="Accepted file types: .png, jpg, jpeg, gif, webp" />
                        <PlaceholderText text="Max size per flie 1mb" />
                    </div>
                    <button type="button" className="w-min button" onClick={triggerImageUpload}>
                        <BsFillPlusCircleFill className="text-blue-500" />
                    </button>
                    <input type="file" id="product-image-upload" className="hidden" multiple accept="image/*" ref={productImageRef} onChange={addProductImage} />
                </div>

                <div className="lg:col-span-2 flex flex-col gap-2">
                    <div className="flex flex-wrap gap-2">
                        {(!productImages.length && !images.length) && (
                            <div className="px-6 py-2 rounded-full bg-gray-300">
                                <PlaceholderText text="No images added" fontColor="black" />
                            </div>
                        )}
                        {computedExistingImages.length ? (
                            computedExistingImages.map((item, index) => (
                                <div key={`item-${index}`} className="w-32 flex flex-col gap-1">
                                    <div className="relative size-32 border-1 border-gray-400 rounded-lg overflow-hidden">
                                        <IoIosCloseCircleOutline className="size-5 absolute top-2 left-2 cursor-pointer text-red-500 z-10" onClick={() => removeExistingImage(item.id)} />
                                        <img src={item.original_url} className="relative size-full object-cover" />
                                    </div>
                                </div>
                            ))
                        ) : ""}
                        {productImages.length ? (
                            productImages.map((item, index) => (
                                <div key={`item-${index}`} className="w-32 flex flex-col gap-1">
                                    <div className="relative size-32 border-1 border-gray-400 rounded-lg overflow-hidden">
                                        <IoIosCloseCircleOutline className="size-5 absolute top-2 left-2 cursor-pointer text-red-500 z-10" onClick={() => removeProductImage(index)} />
                                        <img src={URL.createObjectURL(item)} className="relative size-full object-cover" />
                                    </div>
                                    {errors?.product_images?.[index] &&
                                        <span title={errors.product_images[index].message} className="text-wrap truncate">
                                            <PlaceholderText text={errors.product_images[index].message} />
                                        </span>
                                    }
                                </div>
                            ))
                        ) : ""}
                    </div>
                </div>

                <button type="submit" className="lg:col-span-2 button flex items-center justify-center" disabled={loading}>
                    {loading ?
                        <Spinner /> :
                        <span>Save</span>
                    }
                </button>
            </form>
        </div>
    )
}

const root = document.getElementById("app")

if (root) {
    createRoot(root).render(<CreateProduct />)
}
