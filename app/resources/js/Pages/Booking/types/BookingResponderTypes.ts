import { UserTypes } from "@/Pages/User/types/UserTypes";
import { BookingTypes } from "./BookingTypes";

export interface BookingResponderTypes extends UserTypes {
    booking_id: number,
    name: string,
    user_id: number,
    user?: UserTypes,
    booking?: BookingTypes 
}