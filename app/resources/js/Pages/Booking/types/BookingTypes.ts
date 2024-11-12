import { PaginationTypes } from "@/Pages/Station/types/stationTypes";
import { StationTypes, UserTypes } from "@/Pages/User/types/UserTypes";
import { FilterTypes } from "@/Pages/Station/types/stationTypes";

export interface BookingPaginationTypes extends PaginationTypes {
    data:BookingTypes[]
}

export interface BookingTypes {
    id?: number,
    user?: UserTypes,
    location_name?: string,
    longitude?: number|undefined,
    latitude?:number|undefined,
    status?: string,
    station_id?: number,
    responders?: UserTypes[] | null
    station?: StationTypes
}

export interface BookingFormTypes extends BookingTypes {
    booking_date?: string
}

export interface BookingFilterTypes extends FilterTypes {
    stationId: number,
    ownerId: number,
    status?: string[],
    role?: string
}