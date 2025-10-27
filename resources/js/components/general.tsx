import { CgSpinner } from "react-icons/cg";

export function RequiredComponent() {
    return (
        <span className="text-red-500">*</span>
    )
}

export function PlaceholderText({text = "Not available", fontColor = "text-red-500", className = "text-xs"}) {
    return (
        <span className={`${fontColor} ${className}`}>{text}</span>
    )
}

export function Badge({text}: {text: string}) {
    return (
        <span className={`p-2 rounded-full`}>{text}</span>
    )
}

export function Spinner({text = "Loading"}) {
    return (
        <div className="flex items-center gap-2">
            <CgSpinner className="animate-spin" />
            <span>{text}</span>
        </div>
    )
}
