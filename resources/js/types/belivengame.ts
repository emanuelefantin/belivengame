export interface TySeller {
    id: number;
    name?: string;
    xp?: number;
    salary?: number;
    hired?: boolean;
    hired_at?: Date;
    fired_at?: Date;
    active_project?: boolean;
}

export interface TyDeveloper {
    id: number;
    name?: string;
    xp?: number;
    salary?: number;
    hired?: boolean;
    hired_at?: Date;
    fired_at?: Date;
    active_project?: boolean;
}

export interface TyProject {
    id: number;
    name?: string;
    budget?: number;
    complexity?: number;
    completed?: boolean;
    seller_id: number;
    seller_xp: number;
    generation_progress?: number;
    generation_started_at?: Date;
    generation_completed_at?: Date;
    generation_completed?: boolean;
    developer_id: number;
    developer_xp: number;
    development_progress?: number;
    development_started_at?: Date;
    development_completed_at?: Date;
    development_completed?: boolean;
}


export interface TyGame {
    id: number;
    name?: string;
    sellers: TySeller[];
    developers: TyDeveloper[];
    projects: TyProject[];
    date_start: Date;
    date_current: Date;
    cash_start?: number;
    cash_current?: number;
    cash_month_expenses?: number;
}


